<?php

namespace Five\MailerBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Symfony\Component\HttpFoundation\RequestStack;

class MailerEntityManager extends BaseEntityManager
{
    protected $managerRegistry;
    protected $entityClass;
    protected $manager;
    protected $RequestStack;

    public function __construct( ManagerRegistry $managerRegistry, RequestStack $RequestStack, $entityClass )
    {
        $this->setRequestStack( $RequestStack );
        $this->setManagerRegistry( $managerRegistry );
        $this->setEntityClass( $entityClass );
    }

    public function create( array $data = array() )
    {
        $entity                 = new $this->entityClass();
        
        $data[ 'fromEmail' ]    = $data[ 'from' ];
        $data[ 'toEmail' ]      = $data[ 'to' ];
        $data[ 'templateData' ] = $data[ 'template_data' ];

        $entity->setClassPropertiesValue( $data );        
        $entity->setStatus( true );

        $this->save( $entity );
    }

    public function export( $format = 'csv', $forceDownload = true, $fileName = null )
    {

    }

    protected function exportGenerateCsv()
    {
        $file_name  = date( 'Ymd' ) . '-' . $project . '-' . $phase . '-registration';
        $is_mac_os  = $this->_isMacOs( $Request );

        $em         = $this->_getManagerByProjectPhase( $project, $phase );
        $iterable   = $this->_getExportDataIterable( $project, $phase );

        $handle     = fopen( 'php://memory', 'r+' );
        $header     = array();

        $column_ref_added = false;

        // VERY IMPORTANT - This line sets proper header for Excel to read utf8 properly!
        fprintf( $handle, chr(0xEF).chr(0xBB).chr(0xBF) );

        while( false !== ( $row = $iterable->next() ) )
        {
            $data = $row[ 0 ]->toArray();

            if( !$column_ref_added )
            {
                fputcsv( $handle, array_keys( $data ) );
                $column_ref_added = true;
            }

            $data   = $this->_getNormalizedData( $data, $is_mac_os );
            $values = array_values( $data );

            fputcsv( $handle, $values );
            $em->detach( $row[ 0 ] );
        }

        rewind( $handle );
        $content = stream_get_contents( $handle );
        fclose( $handle );

        return new Response( $content, 200, array(
            'Content-Type'        => 'application/force-download',
            'Content-Disposition' => sprintf( 'attachment; filename="%s_export.csv"', date( 'Ymd' ) . '-' . $project . '-' . $phase . '-registration' )
        ) );        
    }

    public function save( $entity, $flush = true )
    {
        $manager = $this->managerRegistry->getManagerForClass( $this->entityClass );
        
        $entity
            ->setDomain( $this->getRequest()->getHttpHost() )
            ->setIpAddress( $this->getRequest()->getClientIp() )
            ->setUserAgent( $this->getRequest()->headers->get( 'User-Agent' ) )
            ->setUrl( $this->getRequest()->getUri() )
        ;

        parent::save( $entity, $flush );
    }  
}