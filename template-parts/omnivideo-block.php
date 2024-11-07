<?php
/**
 * Template part for displaying the OmniVideo block.
 *
 * @version 1.0.0
 */

/**
 * @var array $args
 */

defined( 'WPINC' ) || die;

use Wpshop\OmniVideo\VideoBlock;
use function Wpshop\OmniVideo\container;

$has_poster_class = $args['poster'] ? ' omnivideo-block--has-poster' : '';
$style_class      = $args['style'] ? ' omnivideo-block--style-' . $args['style'] : '';
$sources_count    = count( array_filter( $args['sources'] ) );

// Проверяем, если иконка является одной из соцсетей
$icons    = $args['icons'];
$icon     = $args['icon']; // Получаем значение иконки
$icon_svg = '';
if ( array_key_exists( $icon, $icons ) ) {
    $icon_svg = $icons[ $icon ]['svg'];
} elseif ( in_array( $icon, [ 'play', 'play-2', 'play-3', 'play-4' ] ) ) {
    // Для предопределенных иконок типа play
    $icon_svg = container()->get( VideoBlock::class )->get_play_icon_svg( $icon );
}

// Обертка с микроразметкой VideoObject
?>
<div class="omnivideo-block<?php echo $has_poster_class . $style_class; ?>" data-default="<?php echo esc_attr( $args['default'] ) ?>" itemscope itemprop="VideoObject" itemtype="https://schema.org/VideoObject">
    <div class="omnivideo-block__inner">
        <?php if ( $args['title'] || $args['description'] ) : ?>
        <div class="omnivideo-content">
            <?php if ( $args['title'] ) : ?>
            <<?php echo $args['title_tag']; ?> class="omnivideo-header"
            itemprop="name"><?php echo esc_html( $args['title'] ); ?></<?php echo $args['title_tag']; ?>>
    <?php endif; ?>
        <?php if ( $args['description'] ) : ?>
            <div class="omnivideo-description" itemprop="description"><?php echo $args['description'] ?></div>
        <?php endif; ?>
    </div><!-- .omnivideo-content -->
    <?php endif; ?>

    <?php if ( ! $args['title'] ): ?>
        <meta itemprop="name" content="<?php echo esc_attr( get_the_title() ) ?>"/>
    <?php endif; ?>

    <div class="omnivideo-body">
        <div class="omnivideo-links omnivideo-links--count-<?php echo $sources_count; ?>">
            <?php foreach ( $args['sources'] as $type => $url ) :
            if ( ! empty( $url ) ) :
            $link_element = $args['pseudo'] ? 'span' : 'a';
            $link         = $args['pseudo'] ? "data-type='{$type}' data-url='{$url}'" : "href='{$url}' data-type='{$type}' data-url='{$url}'";
            $svg          = $args['show_icons'] ? $icons[ $type ]['svg'] : '';
            $title_span   = $args['show_titles'] ? "<span>{$icons[$type]['title']}</span>" : '';
            $color        = $icons[ $type ]['color'];
            ?>
            <<?php echo $link_element; ?> class="omnivideo-link" <?php echo $link; ?>
            title="<?php echo $icons[ $type ]['title']; ?>" style="--omnivideo-link-color: <?php echo $color; ?>;">
            <?php echo $svg . ' ' . $title_span; ?>
        </<?php echo $link_element; ?>>
        <?php endif;
        endforeach; ?>
    </div><!-- .omnivideo-links -->

    <div class="omnivideo-embed-container">
        <div class="omnivideo-embed omnivideo-aspect-16-9">
            <?php if ( $args['poster'] ) : ?>
                <img src="<?php echo esc_url( $args['poster'] ); ?>" alt="" class="omnivideo-poster" itemprop="thumbnailUrl">
                <?php if ( $icon_svg ) : ?>
                    <div class="omnivideo-icon"><?php echo $icon_svg; ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div><!-- .omnivideo-embed -->
    </div><!-- .omnivideo-embed-container -->

</div><!-- .omnivideo-body -->

<?php if ( $args['show_partner'] ): ?>
    <div class="omnivideo-partner">
		<?php echo Wpshop\OmniVideo\get_partner_link( $args['partner_text'], 'OmniVideo' ) ?>
    </div><!-- .omnivideo-partner -->
<?php endif ?>

</div><!-- .omnivideo-block__inner -->

<meta itemprop="uploadDate" content="<?php echo esc_attr( date( 'c' ) ); ?>">

</div><!-- .omnivideo-block -->
