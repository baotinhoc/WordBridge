<?php
/**
 * @version     $Id$
 * @package  Wordbridge
 * @copyright   Copyright (C) 2010 Cognidox Ltd
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

defined('_JEXEC') or die( 'Restricted access' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

?>
<div class="wordbridge_blog blog<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
    <div class="wordbridge_blog_header">
    <?php if ( $this->params->get( 'show_page_heading', 1 ) ) : ?>
        <h2><span class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
        <?php echo sprintf( '<a href="%s">%s</a>',
                            JRoute::_( $this->blogLink ),
                            $this->escape( $this->blog_title ) ); ?>
        </span></h2>
        <?php if ( !empty( $this->blogTitle ) ): ?>
            <?php echo $this->escape( $this->blogTitle ); ?>
        <?php endif; ?>
    <?php endif; ?>
    </div>
    <div class="wordbridge_entry">
        <h2 class="wordbridge_title contentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
            <?php echo $this->escape( $this->title ); ?>
        </h2>
        <span class="wordbridge_date"><?php echo WordbridgeHelper::wordBridgeStrftime( '%B %e, %Y', $this->date, true ); ?></span>
        <div class="wordbridge_content">
        <?php 
            if ( $this->params->get( 'wordbridge_show_links' ) == 'no' )
            {
                $br_pos = strrpos( $this->content, '<br />' );
                if ( $br_pos > 0 )
                {
                    echo substr( $this->content, 0, $br_pos );
                }
                else
                {
                    echo $this->content;
                }
            }
            else
            {
                echo $this->content;
            }
        ?>
        </div>
        <?php if ( !empty( $this->categories ) ): ?>
        <div class="wordbridge_categories">
        <?php
            $seenCategories = array();
            $categoryLinkList = array();

            $lastWasTag = false;
            $blogname = $this->params->get( 'wordbridge_blog_name' );
            if ( empty( $blogname ) || ! function_exists ( 'curl_init' ) )
            {
                return null;
            }
            $blogInfo = WordbridgeHelper::getBlogByName( $blogname );
            foreach ( $this->categories as $category )
            {
                if ( array_key_exists( strtolower( $category ), $seenCategories ) || ( $lastWasTag && WordbridgeHelper::isCategory( $blogInfo['uuid'], $category ) ) )
                {
                    $slug = WordbridgeHelper::nameToSlug( $category . "-2" );
                    $categoryLinkList[] = sprintf( '<a href="%s" class="wordbridge_category">%s</a>',
                                   $this->blogLink . '&c=' .
                                   $slug . '&view=category' .
                                   '&name=' . urlencode( $category . "-2" ),
                                   $this->escape( $category ) );
                }
                else
                {
                    $slug = WordbridgeHelper::nameToSlug( $category );
                    $seenCategories[strtolower($category)] = true;
                    $categoryLinkList[] = sprintf( '<a href="%s" class="wordbridge_category">%s</a>',
                                   $this->blogLink . '&c=' .
                                   $slug . '&view=category' .
                                   '&name=' . urlencode( $category ),
                                   $this->escape( $category ) );
                }
                $lastWasTag = ( $lastWasTag || WordbridgeHelper::isTag( $blogInfo['uuid'], $category ) );
            }

            echo JText::_( 'COM_WORDBRIDGE_POSTED_IN' ). ': <span class="wordbridge_categories">' .
                 implode( ', ', $categoryLinkList ) . '</span>';
        ?>
        </div>
        <?php endif; ?>
    </div>
    <?php
        if ( $this->jcomments != false )
        {
            echo sprintf( '<div class="wordbridge_jcomments">%s</div>', $this->jcomments );
        }
    ?>
</div>
<?php
if ( $this->convertLinks )
{
    JHTML::_( 'behavior.mootools' );
    echo $this->loadTemplate( 'convertlinks' );
}
