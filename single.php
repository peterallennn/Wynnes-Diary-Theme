<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Wynne\'s_Diary
 */
global $post;
$category = get_the_category($post->ID);
$sidebar = false;
if(isset($category[0]) && $category[0]->parent == 259) { // Sidebar
  $sidebar = true;
} else {
  $month = $category;
  $year = get_term($month[0]->parent, 'category');
  $year_name = $year->name;
  // Get the abbreviation of the month
  $month_name = date('M', strtotime($month[0]->name . ' 20th 2018'));
}

get_header();
?>
  <div class="post-body">
    <div class="post-content group">
      <div class="heading">
        <?php if($sidebar) : ?>
          <a href="<?= get_category_link($category[0]->term_id) ?>" class="back-to-timeline">&#8249; Back to <?= $category[0]->name ?></a>
        <?php else : ?>
          <a href="/the-diary/<?= $year_name ?>/<?= $month_name ?>" class="back-to-timeline">&#8249; Back to <?= $month[0]->name ?> <?= $year->name ?>'s Timeline</a>
        <?php endif; ?>
      </div>
      <?= apply_filters('the_content', $post->post_content) ?>
    </div>
    <a href="#" class="back-to-top"></a>
  </div>
  <span class="post-body-bottom"></span>
<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
//-->
</script>
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<?php
get_sidebar();
get_footer();
