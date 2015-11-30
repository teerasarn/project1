<?php
namespace Five\CuisinesVerdunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Five\CuisinesVerdunBundle\Entity\GalleryCategory;
use Five\CuisinesVerdunBundle\Entity\GalleryImage;
use Five\CuisinesVerdunBundle\Entity\Gallery;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


error_reporting(E_ALL);

class DefaultController extends Controller
{
    public function indexAction( ) {
    	
    	  
        return $this->render('FiveCuisinesVerdunBundle:Default:index.html.twig');
    }

    public function fetchCarouselAction( ) {
        $response = array();
        // styles, each style
        $response['styles'] = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getImages();
        // material, each material with images
        $response['materials'] = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getImages();
        return new JsonResponse(array($response));
    }


    public function setMappingAction()
    {
/*$kStyles = array(
    1 => 11,
2 => 12,
5 => 13,
6 => 14);*/

/*$kStyles = array(
    18 => 15,
19 => 16,
20 => 17,
21 => 30);*/

/*$materials = array(
1 => 18,
2 => 19,
3 => 20,
4 => 21,
5 => 22,
6 => 23
);*/

//bathroom
$materials = array(
17 => 24,
18 => 25,
19 => 26,
20 => 27,
21 => 28,
22 => 29

);
        $styles = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getList('bathroom');
        //$images = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getImages('bathroom');
        $images = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getImages('bathroom');
        //$last = end($images);
        $cat = null;
        $totalCnt = 0;
        foreach ($images as $groups)
        {
            $G = new Gallery();
            $G->setContext( 'bathroom' );
            $G->setPosition( $totalCnt );
            $totalCnt++;
            $cnt = 0;
            foreach ($groups as $image)
            {


                    //$G = new Gallery();
                    $Category = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->find( $materials[ $image[ 'category' ] ] );

                //if( $cat != $image['category'] )
                //{
                    $cat = $image[ 'category' ];
                    if( $Category )
                    {
                        $G->addCategory( $Category );
                    }

                //}

                $PicEntity = new GalleryImage();
                $PicEntity->setSrc( '/uploads/'.$image[ 'url' ] );
                $PicEntity->setSrcThumb( '/uploads/thumbs/'.$image[ 'url' ] );
                $PicEntity->setDescriptionFr( $image[ 'fr' ] );
                $PicEntity->setDescriptionEn( $image[ 'en' ] );
                $PicEntity->setPosition( $cnt );

                $PicEntity->setGallery( $G );
                $this->get( 'doctrine' )->getManager()->persist( $PicEntity );

                echo $image[ 'url' ];
                $cnt++;
            }
            $this->get( 'doctrine' )->getManager()->persist( $G );

        }

        $this->get( 'doctrine' )->getManager()->flush();
        var_dump($styles);
        var_dump($materials);
        //var_dump($last);
    }
    // public function emailTestAction()
    // {
    //     $User = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:User')->find(66);
    //     //$User = $Users[ 10 ];
    //
    //     $first_name  = $User->getFirstName();
    //     $last_name   = $User->getLastName();
    //     $email       = $User->getEmail();
    //     $phone       = $User->getPhone();
    //     $postal_code = $User->getPostalCode();
    //     $branchName  = 'Terrebonne';
    //     $dispo       = explode( ',', $User->getAvailability() );
    //     $options     = explode( ',', $User->getOptions() );
    //     $loc         = $User->getSiteLanguage();
    //     $project     = $User->getProject();
    //
    //     $emailMessage = \Swift_Message::newInstance()
    //         ->setSubject('asdasdasd')
    //         ->setFrom('info@cuisinesverdun.com')
    //         ->setTo('stephen@five.ca')
    //         ->setBody(
    //             $this->renderView(
    //                 'FiveCuisinesVerdunBundle::confirm.email.fr.html.twig'
    //             )
    //         )
    //         ->setContentType( 'text/html' )
    //     ;
    //     $this->get('mailer')->send($emailMessage);
    //
    //     return $this->render(
    //                 'FiveCuisinesVerdunBundle::confirm.email.en.html.twig'
    //     );
    //
    // }

    public function generateThumb( $file,  $ext )
    {
        $uploadsPath = __DIR__.'/../../../../web/uploads/';
        $thumbsPath = __DIR__.'/../../../../web/uploads/thumbs/';
        $size = 100;
        if( file_exists($uploadsPath.$file) && !file_exists($thumbsPath.$file) )
        {
            list( $W, $H, $type, $attr ) = @getimagesize( $uploadsPath.$file );
            $ratio_orig = ( $W / $H );

            if( $W <= $H )
            {
                $height = $size;
                $width  = $height * $ratio_orig;
            }
            else
            {
                $width  = $size;
                $height = $width / $ratio_orig;
            }

            // Resample
            $image_p = imagecreatetruecolor($width, $height);
            $img     = $uploadsPath . $file;

            switch( $ext )
            {
                case 'jpeg':
                case 'jpg':
                    $image = @imagecreatefromjpeg($img);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $W, $H );
                    imagejpeg( $image_p, $thumbsPath . $file, 85 );
                break;
                case 'gif':
                    $image = @imagecreatefromgif($img);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $W, $H );
                    imagegif( $image_p, $thumbsPath . $file, 85 );
                break;
                /*case 'png':
                    $image = @imagecreatefrompng($img);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $W, $H );
                    imagepng( $image_p, $thumbsPath . $file );
                break;*/
            }
            @imagedestroy( $image_p );
        }

    }

    public function mapImagesToGalleriesAction()
    {


        $cats = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findAll();
        $rooms = array($cats[0],$cats[1]);

        $styles = array( $cats[2],$cats[3],$cats[4],$cats[5],$cats[6] );

        $cnt = 0;

        foreach ($styles as $style )
        {

            $G = new Gallery();
            $G->addCategory( $rooms[0] );
            $G->addCategory( $style );
            $G->setTitleFr( $style->getTitleFr() );
            $G->setTitleEn( $style->getTitleEn() );
            $G->getDescriptionFr( $style->getDescriptionFr() );
            $G->getDescriptionEn( $style->getDescriptionEn() );
            $G->setPosition( $cnt );

            //$pictures = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findImagesByCategories( array( 'bathroom', $style->getName() ) , 'realisation', '*' );


            $this->get( 'doctrine' )->getManager()->persist( $G );

            $cnt++;

            $G2 = new Gallery();
            $G2->addCategory( $rooms[1] );
            $G2->addCategory( $style );
            $G2->setTitleFr( $style->getTitleFr() );
            $G2->setTitleEn( $style->getTitleEn() );
            $G2->getDescriptionFr( $style->getDescriptionFr() );
            $G2->getDescriptionEn( $style->getDescriptionEn() );
            $G2->setPosition( $cnt );
            $this->get( 'doctrine' )->getManager()->persist( $G2 );
            $cnt++;
        }

        $this->get( 'doctrine' )->getManager()->flush();
    }

    public function generateGalleriesAction()
    {
    //$em = $this->getDoctrine()->getManager();
    //$Pictures = $this->getDoctrine()->getRepository( "FiveAdminBundle:Picture" )->findAll();

/*    foreach( $Pictures as $Picture )
    {
        if( file_exists( $Picture->getAbsolutePath() ) )
        {
            $Picture->generateThu
        }
    }*/
   //$Images = $this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findAll();


    $path = '/Users/simon/Projects/Five/CuisinesVerdun.com/web/uploads/';
    $cnt = 0;
    $list = array();
    if ($handle = opendir( '/Users/simon/Projects/Five/CuisinesVerdun.com/web/uploads' )) {
        while (false !== ($entry = readdir($handle))) {
            $expl = explode( '.', $entry );
            $ext = end( $expl );
            echo strtolower( $ext );
            if ($entry != "." && $entry != ".." && in_array( strtolower( $ext ), array( 'jpg', 'jpeg', 'gif', 'png' ) ) ) {
                echo $entry . "\n";
  //              array_push($list, $entry);
/*                $explSrc = explode( '/', $Images[ $cnt ]->getSrc() );
                $src = end( $explSrc );
                if( $path . $entry != $path . $src )
                {
                    rename($path . $entry, $path . $src);
                }*/
                $this->generateThumb( $entry,  $ext );
                $cnt++;
            }
        }
        closedir($handle);

        //echo count($list );
    }

/*        $fs = new Filesystem();

        $fs->rename('/tmp/processed_video.ogg', '/path/to/store/video_647.ogg');
        // rename a directory
        $fs->rename('/tmp/files', '/path/to/store/files'); */
/*


        $categories = array( 'bathroom', 'modern' );
        $Categories = $this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findImagesByCategories( $categories );

        return new JsonResponse( json_encode( $Categories ) );*/
        //var_dump($Categories);

/*        $len = 0;


        while( $len < 200 )
        {
            $GI = new GalleryImage();
            //$GI->setTitle( 'Image ' . $len );

            $GI->addCategory( $Categories[ rand(0,5) ] );
            $GI->addCategory( $Categories[ rand(0,5) ] );
            $GI->addCategory( $Categories[ rand(0,5) ] );
            $GI->setSrc( 'http://lorempixel.com/1280/1024/sports/'.( $len + 1 ).'/ImageId-' . ( $len + 1 ) );
            $GI->setSrcThumb( 'http://lorempixel.com/125/125/sports/'.( $len + 1 ).'/ImageId-' . ( $len + 1 ) );
            $GI->setTitleFr( 'Image title FR: ' . ( $len + 1 ) );
            $GI->setTitleEn( 'Image title EN: ' . ( $len + 1 ) );
            $GI->setDescriptionFr( 'Image Desc FR: ' . ( $len + 1 ) );
            $GI->setDescriptionEn( 'Image Desc EN: ' . ( $len + 1 ) );
            $GI->setPosition( $len );

            $em->persist( $GI );

            $len++;

            echo $len;
        }


        $em->flush();

        return new Response( 'aljsdhajsdhas '. $Categories[0]->getName() );*/
    }
}
