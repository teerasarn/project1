// Register a template definition set named "default".
CKEDITOR.addTemplates( 'default',
{
	// The name of the subfolder that contains the preview images of the templates.
	imagesPath : CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),
 
	// Template definitions.
	templates :
		[
			{
				title: 'Blog template 01',
				image: 'blogtemplate_01.gif',
				description: 'Edit image and text',
				html:
                    '<div class="row intro_text">'+
                       ' <div class="col-md-12">'+
                            '<h1>Sed up perspiciatis!</h1>'+
                            '<h5>Omnis iste natus error sit voluptatem</h5>'+
                            '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'+
                            '<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>'+
                        '</div>'+
                   ' </div>'+
		   '<div class="row first_content">'+
                        '<div class="col-md-6"><img src="/uploads/blog/9151d4ee784c6bd54d8886dc89a607ee67459424.jpg" alt="" class="toleft"/></div>'+
                        '<div class="col-md-6">'+
                            '<h4>Reprehenderit qui in ea voluptate</h4>'+
                            '<p class="textright">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. </p>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row second_content no_line">'+
                        '<div class="col-md-5">'+
                            '<h4>Lorem ipsum dolor sit amet</h4>'+
                            '<p class="textleft">Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. </p>'+
                            '<p class="textleft">Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>'+
                        '</div>'+
                        '<div class="col-md-7">'+
                        '<img src="/uploads/blog/981f0ea560878e7f0dd1fb3351cbab7b3602ac7c.jpg" alt="" class="toright"/>'+
                        '</div>'+
                    '</div>'
                   
			}
		] 
                
});
