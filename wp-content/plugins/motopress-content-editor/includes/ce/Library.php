<?php
require_once 'Element.php';
require_once 'Group.php';
require_once 'Object.php';

/**
 * Description of MPCELibrary
 *
 */
class MPCELibrary {
    private $library = array();
    private $skippedGroups = array();
    public static $isAjaxRequest;

    /**
     * @global stdClass $motopressCELang
     */
    public function __construct() {
        global $motopressCELang;
        self::$isAjaxRequest = $this->isAjaxRequest();

        /* Objects */
        //grid
        $rowObj = new MPCEObject(MPCEShortcode::PREFIX . 'row', $motopressCELang->CERowObjName, null, null, null, MPCEObject::ENCLOSED);
        $rowInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'row_inner', $motopressCELang->CERowInnerObjName, null, null, null, MPCEObject::ENCLOSED);
        $spanObj = new MPCEObject(MPCEShortcode::PREFIX . 'span', $motopressCELang->CESpanObjName, null, array(
            'col' => array(
                'type' => 'number',
                'values' => range(1, 12),
                'default' => '12'
            )
        ), null, MPCEObject::ENCLOSED);
        $spanInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'span_inner', $motopressCELang->CESpanInnerObjName, null, array(
            'col' => array(
                'type' => 'number',
                'values' => range(1, 12),
                'default' => '12'
            )
        ), null, MPCEObject::ENCLOSED);

        //text
        $textObj = new MPCEObject(MPCEShortcode::PREFIX . 'text', $motopressCELang->CETextObjName, 'text.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => $motopressCELang->CETextObjButtonDesc . ' ' . $motopressCELang->CETextObjName,
                'text' => $motopressCELang->edit . ' ' . $motopressCELang->CETextObjName
            )
        ), 20, MPCEObject::ENCLOSED);

        $headingObj = new MPCEObject(MPCEShortcode::PREFIX . 'heading', $motopressCELang->CEHeadingObjName, 'heading.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => $motopressCELang->CETextObjButtonDesc . ' ' . $motopressCELang->CEHeadingObjName,
                'text' => $motopressCELang->edit . ' ' . $motopressCELang->CEHeadingObjName
            )
        ), 10, MPCEObject::ENCLOSED);

        $codeObj = new MPCEObject(MPCEShortcode::PREFIX . 'code', $motopressCELang->CECodeObjName, 'wordpress.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => $motopressCELang->CETextObjButtonDesc . ' ' . $motopressCELang->CECodeObjName,
                'text' => $motopressCELang->edit . ' ' . $motopressCELang->CECodeObjName
            )
        ), 30, MPCEObject::ENCLOSED);

        //image
        $imageObj = new MPCEObject(MPCEShortcode::PREFIX . 'image', $motopressCELang->CEImageObjName, 'image.png', array(
            'id' => array(
                'type' => 'image',
                'label' => $motopressCELang->CEImageObjSrcLabel,
                'default' => '',
                'description' => $motopressCELang->CEImageObjSrcDesc,
                'autoOpen' => 'true'
            ),
            'link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEImageObjLinkLabel,
                'default' => '#',
                'description' => $motopressCELang->CEImageObjLinkDesc,
                'disabled' => 'true'
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEButtonObjTargetLabel,
                'default' => 'false',
                'disabled' => 'true'
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                )
            )
        ), 10);

        $imageSlider = new MPCEObject(MPCEShortcode::PREFIX . 'image_slider', $motopressCELang->CEImageSliderObjName, 'image-slider.png', array(
            'ids' => array(
                'type' => 'multi-images',
                'label' => $motopressCELang->CEImageSliderObjIdsLabel,
                'default' => '',
                'description' => $motopressCELang->CEImageSliderObjIdsDesc,
                'text' => $motopressCELang->CEImageSliderObjIdsText,
                'autoOpen' => 'true'
            ),
            'animation' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEImageSliderObjAnimationLabel,
                'default' => 'fade',
                'description' => $motopressCELang->CEImageSliderObjAnimationDesc,
                'list' => array(
                    'fade' => $motopressCELang->CEImageSliderObjAnimationFade,
                    'slide' => $motopressCELang->CEImageSliderObjAnimationSlide
                ),
                'disabled' => 'true'
            ),
            'smooth_height' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjSmoothHeightLabel,
                'default' => 'false',
                'description' => $motopressCELang->CEImageSliderObjSmoothHeightDesc,
                'dependency' => array(
                    'parameter' => 'animation',
                    'value' => 'slide'
                ),
                'disabled' => 'true'
            ),
            'slideshow' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjAutoplayLabel,
                'default' => 'true',
                'description' => $motopressCELang->CEImageSliderObjAutoplayDesc,
                'disabled' => 'true'
            ),
            'slideshow_speed' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEImageSliderObjSlideshowSpeedLabel,
                'default' => 7,
                'min' => 1,
                'max' => 20,
                'dependency' => array(
                    'parameter' => 'slideshow',
                    'value' => 'true'
                ),
                'disabled' => 'true'
            ),
            'control_nav' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEImageSliderObjControlNavLabel,
                'default' => 'true',
                'disabled' => 'true'
            )
        ), 20);

        //button
        $buttonObj = new MPCEObject(MPCEShortcode::PREFIX . 'button', $motopressCELang->CEButtonObjName, 'button.png', array(
            'text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEButtonObjTextLabel,
                'default' => $motopressCELang->CEButtonObjName
            ),
            'link' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEButtonObjLinkLabel,
                'default' => '#',
                'description' => $motopressCELang->CEButtonObjLinkDesc
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEButtonObjTargetLabel,
                'default' => 'false',
                'disabled' => 'true'
            ),
            'color' => array(
                'class-prefix' => 'mp-button-',
                'type' => 'color-select',
                'label' => $motopressCELang->CEButtonObjColorLabel,
                'default' => 'default',
                'list' => array(
                    'default' => $motopressCELang->CESilver,
                    'red' => $motopressCELang->CERed,
                    'pink-dreams' => $motopressCELang->CEPinkDreams,
                    'warm' => $motopressCELang->CEWarm,
                    'hot-summer' => $motopressCELang->CEHotSummer,
                    'olive-garden' => $motopressCELang->CEOliveGarden,
                    'green-grass' => $motopressCELang->CEGreenGrass,
                    'skyline' => $motopressCELang->CESkyline,
                    'aqua-blue' => $motopressCELang->CEAquaBlue,
                    'violet' => $motopressCELang->CEViolet,
                    'dark-grey' => $motopressCELang->CEDarkGrey,
                    'black' => $motopressCELang->CEBlack
                )
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjSizeLabel,
                'default' => 'default',
                'list' => array(
                    'large' => $motopressCELang->CELarge,
                    'default' => $motopressCELang->CEMiddle,
                    'small' => $motopressCELang->CESmall,
                    'mini' => $motopressCELang->CEMini
                )
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'left',
                'list' => array(
                    'left' => $motopressCELang->CELeft,
                    'center' => $motopressCELang->CECenter,
                    'right' => $motopressCELang->CERight
                )
            )
        ), 10);

        //media
        $videoObj = new MPCEObject(MPCEShortcode::PREFIX . 'video', $motopressCELang->CEVideoObjName, 'video.png', array(
            'src' => array(
                'type' => 'video',
                'label' => $motopressCELang->CEVideoObjSrcLabel,
                'default' => MPCEShortcode::DEFAULT_VIDEO,
                'description' => $motopressCELang->CEVideoObjSrcDesc
            )
        ), 10);

        //other
        $gMapObj = new MPCEObject(MPCEShortcode::PREFIX.'gmap', $motopressCELang->CEGoogleMapObjName, 'map.png', array(
            'address' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEGoogleMapObjAddressLabel,
                'default' => 'Sidney, New South Wales, Australia',
                'description' => $motopressCELang->CEGoogleMapObjAddressDesc
            ),
            'zoom' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEGoogleMapObjZoomLabel,
                'default' => 13,
                'min' => 0,
                'max' => 20
            )
        ), 60, null, MPCEObject::RESIZE_ALL);

        $spaceObj = new MPCEObject(MPCEShortcode::PREFIX . 'space', $motopressCELang->CESpaceObjName, 'space.png', null, 50, null, MPCEObject::RESIZE_ALL);

        $embedObj = new MPCEObject(MPCEShortcode::PREFIX . 'embed', $motopressCELang->CEEmbedObjName, 'code.png', array(
            'data' => array(
                'type' => 'longtext64',
                'label' => $motopressCELang->CEEmbedObjPasteCode,
                'default' => 'PGk+UGFzdGUgeW91ciBjb2RlIGhlcmUuPC9pPg==',
                'description' => $motopressCELang->CEEmbedObjPasteCodeDescription
            ),
            'fill_space' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEEmbedObjFill,
                'default' => 'true',
                'description' => $motopressCELang->CEEmbedObjFillDescription
            )
        ), 40);

        $quotesObj = new MPCEObject(MPCEShortcode::PREFIX . 'quote', $motopressCELang->CEQuotesObjName, 'quotes.png', array(
            'cite' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEQuotesObjCiteLabel,
                'default' => 'John Smith',
                'description' => $motopressCELang->CEQuotesObjCiteDesc,
            ),
            'cite_url' => array(
                'type' => 'link',
                'label' => $motopressCELang->CEQuotesObjUrlLabel,
                'default' => '#',
                'description' => $motopressCELang->CEQuotesObjUrlDesc,
            ),
            'quote_content' => array(
                'type' => 'longtext',
                'label' => $motopressCELang->CEQuotesObjContentLabel,
                'default' => 'Lorem ipsum dolor sit amet.'
            )
        ), 40, MPCEObject::ENCLOSED);

        $membersObj = new MPCEObject(MPCEShortcode::PREFIX . 'members_content', $motopressCELang->CEMembersObjName, 'members.png', array(
            'message' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEMembersObjMessageLabel,
                'default' => $motopressCELang->CEMembersObjMessageDefault,
                'description' => $motopressCELang->CEMembersObjMessageDesc,
            ),
            'login_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEMembersObjLoginTextLabel,
                'default' => $motopressCELang->CEMembersObjLoginTextDefault,
                'description' => $motopressCELang->CEMembersObjLoginTextDesc,
            ),
            'members_content' => array(
                'type' => 'longtext',
                'label' => $motopressCELang->CEMembersObjContentLabel,
                'default' => $motopressCELang->CEMembersObjContentValue,
            ),
        ), 50, MPCEObject::ENCLOSED);

        $socialsObj = new MPCEObject(MPCEShortcode::PREFIX . 'social_buttons', $motopressCELang->CESocialsObjName, 'social-buttons.png', array(
            'size' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjSizeLabel,
                'default' => 'motopress-buttons-32x32',
                'list' => array(
                    'motopress-buttons-32x32' => $motopressCELang->CESocialsObjSizeNormal,
                    'motopress-buttons-64x64' => $motopressCELang->CESocialsObjSizeLarge,
                ),
            ),
            'style' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjStyleLabel,
                'default' => 'motopress-buttons-square',
                'list' => array(
                    'motopress-buttons-square' => $motopressCELang->CESocialsObjStyleSquare,
                    'motopress-buttons-rounded' => $motopressCELang->CESocialsObjStyleRounded,
                    'motopress-buttons-circular' => $motopressCELang->CESocialsObjStyleCircular,
                    'motopress-buttons-volume' => $motopressCELang->CESocialsObjStyleVolume,
                ),
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjAlignLabel,
                'default' => 'motopress-text-align-left',
                'list' => array(
                    'motopress-text-align-left' => $motopressCELang->CELeft,
                    'motopress-text-align-center' => $motopressCELang->CECenter,
                    'motopress-text-align-right' => $motopressCELang->CERight
                )
            )
        ), 20, MPCEObject::ENCLOSED);

        $googleChartsObj = new MPCEObject(MPCEShortcode::PREFIX . 'google_chart', $motopressCELang->CEGoogleChartsObjName, 'chart.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEObjTitleLabel,
                'default' => 'Company Performance'
            ),
            'type' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEGoogleChartsObjTypeLabel,
                'description' => $motopressCELang->CEGoogleChartsObjTypeDesc,
                'default' => 'ColumnChart',
                'list' => array(
                    'ColumnChart' => $motopressCELang->CEGoogleChartsObjTypeListColumn,
                    'BarChart' => $motopressCELang->CEGoogleChartsObjTypeListBar,
                    'AreaChart' => $motopressCELang->CEGoogleChartsObjTypeListArea,
                    'SteppedAreaChart' => $motopressCELang->CEGoogleChartsObjTypeListStepped,
                    'PieChart' => $motopressCELang->CEGoogleChartsObjTypeListPie,
                    'PieChart3D' => $motopressCELang->CEGoogleChartsObjTypeList3D,
                    'LineChart' => $motopressCELang->CEGoogleChartsObjTypeListLine,
                    'Histogram' => $motopressCELang->CEGoogleChartsObjTypeListHistogram
                ),
                'disabled' => 'true'
            ),
            'donut' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEGoogleChartsObjDonutLabel,
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' =>'PieChart'
                )
            ),
            'table' => array(
                'type' => 'longtext-table',
                'label' => $motopressCELang->CEObjTableDataLabel,
                'description' => $motopressCELang->CEGoogleChartsObjDataDesc,
                'default' => 'Year,Sales,Expenses<br />2004,1000,400<br />2005,1170,460<br />2006,660,1120<br />2007,1030,540',
                'saveInContent' => 'true'
            )
        ), 30, MPCEObject::ENCLOSED, MPCEObject::RESIZE_ALL);

        $tableObj = new MPCEObject(MPCEShortcode::PREFIX . 'table', $motopressCELang->CETableObjName, 'table.png', array(
            'table' => array(
                'type' => 'longtext-table',
                'label' => $motopressCELang->CEObjTableDataLabel,
                'default' => 'Year,Sales,Expenses<br />2004,1000,400<br />2005,1170,460<br />2006,660,1120<br />2007,1030,540',
                'description' => $motopressCELang->CEObjTableDataDesc,
                'saveInContent' => 'true'
            ),
            'style' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjStyleLabel,
                'default' => 'none',
                'list' => array(
                    'none' => $motopressCELang->CETableObjListDefault,
                    'silver' => $motopressCELang->CETableObjListLight,
                ),
                'disabled' => 'true'
            )
        ), 10, MPCEObject::ENCLOSED);

        //wordpress
        // archives
        $wpArchiveObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_archives', $motopressCELang->CEwpArchives, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpArchives,
                'description' => $motopressCELang->CEwpArchivesDescription
            ),
            'dropdown' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpDisplayAsDropDown,
                'default' => '',
                'description' => ''
            ),
            'count' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpShowPostCounts,
                'default' => '',
                'description' => ''
            )
        ), 45);

        // calendar
        $wpCalendarObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_calendar', $motopressCELang->CEwpCalendar, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpCalendar,
                'description' => $motopressCELang->CEwpCalendarDescription
            )
        ), 30);

        // wp_categories
        $wpCategoriesObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_categories', $motopressCELang->CEwpCategories, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpCategories,
                'description' => $motopressCELang->CEwpCategoriesDescription
            ),
            'dropdown' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpDisplayAsDropDown,
                'default' => '',
                'description' => ''
            ),
            'count' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpShowPostCounts,
                'default' => '',
                'description' => ''
            ),
            'hierarchy' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpCategoriesShowHierarchy,
                'default' => '',
                'description' => ''
            )
        ), 40);

        // wp_navmenu
        $wpCustomMenu_menus = get_terms('nav_menu');
        $wpCustomMenu_array = array();
        $wpCustomMenu_default = '';
        if ($wpCustomMenu_menus){
            foreach($wpCustomMenu_menus as $menu){
                if (empty($wpCustomMenu_default))
                    $wpCustomMenu_default = $menu->slug;
                $wpCustomMenu_array[$menu->slug] = $menu->name;
            }
        }else{
            $wpCustomMenu_array['no'] = $motopressCELang->CEwpCustomMenuNoMenus;
        }
        $wpCustomMenuObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_navmenu', $motopressCELang->CEwpCustomMenu, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpCustomMenu,
                'description' => $motopressCELang->CEwpCustomMenuDescription
            ),
            'nav_menu' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpCustomMenuSelectMenu,
                'default' => $wpCustomMenu_default,
                'description' => '',
                'list' => $wpCustomMenu_array
            )
        ), 10);

        // wp_meta
        $wpMetaObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_meta', $motopressCELang->CEwpMeta, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpMeta,
                'description' => $motopressCELang->CEwpMetaDescription
            )
        ), 55);

        // wp_pages
        $wpPagesObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_pages', $motopressCELang->CEwpPages, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpPages,
                'description' => $motopressCELang->CEwpPagesDescription
            ),
            'sortby' => array(
                'type' => 'select',
                'label' => $motopressCELang->CESortBy,
                'default' => 'menu_order',
                'description' => '',
                'list' => array(
                    'post_title' => $motopressCELang->CESortByPageTitle,
                    'menu_order' => $motopressCELang->CESortByPageOrder,
                    'ID' => $motopressCELang->CESortByPageID
                ),
            ),
            'exclude' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEExclude,
                'default' => '',
                'description' => $motopressCELang->CEwpPagesExcludePages
            )
        ), 15);

        // wp_posts
        $wpPostsObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_posts', $motopressCELang->CEwpRecentPosts, 'wordpress.png', array(
            'title' => array(
                    'type' => 'text',
                    'label' => $motopressCELang->CEParametersTitle,
                    'default' => $motopressCELang->CEwpRecentPosts,
                    'description' => $motopressCELang->CEwpRecentPostsDescription
            ),
            'number' => array(
                    'type' => 'text',
                    'label' => $motopressCELang->CEwpRecentPostsNumber,
                    'default' => '5',
                    'description' => ''
            ),
            'show_date' => array(
                    'type' => 'checkbox',
                    'label' => $motopressCELang->CEwpRecentPostsDisplayDate,
                    'default' => '',
                    'description' => ''
            )
        ), 20);

        // wp_comments
        $wpRecentCommentsObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_comments', $motopressCELang->CEwpRecentComments, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpRecentComments,
                'description' => $motopressCELang->CEwpRecentCommentsDescription
            ),
            'number' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpRecentCommentsNumber,
                'default' => '5',
                'description' => ''
            )
        ), 25);

        // wp_rss
        $wpRSSObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_rss', $motopressCELang->CEwpRSS, 'wordpress.png', array(
            'url' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpRSSUrl,
                'default' => 'http://www.getmotopress.com/feed/',
                'description' => $motopressCELang->CEwpRSSUrlDescription
            ),
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpRSSFeedTitle,
                'default' => '',
                'description' => $motopressCELang->CEwpRSSFeedTitleDescription
            ),
            'items' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpRSSQuantity,
                'default' => 9,
                'description' => $motopressCELang->CEwpRSSQuantityDescription,
                'list' => range(1, 20),
            ),
            'show_summary' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpRSSDisplayContent,
                'default' => '',
                'description' => ''
            ),
            'show_author' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpRSSDisplayAuthor,
                'default' => '',
                'description' => ''
            ),
            'show_date' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpRSSDisplayDate,
                'default' => '',
                'description' => ''
            )
        ), 50);

    // search
        $wpSearchObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_search', $motopressCELang->CEwpRSSSearch, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpRSSSearch,
                'description' => $motopressCELang->CEwpRSSSearchDescription
            )
        ), 35);

        // tag cloud
        $wpTagCloudObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_tagcloud', $motopressCELang->CEwpTagCloud, 'wordpress.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => $motopressCELang->CEwpTags,
                'description' => $motopressCELang->CEwpTagCloudDescription
            ),
            'taxonomy' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpTagCloudTaxonomy,
                'default' => 10,
                'description' => '',
                'list' => array(
                    'post_tag' => $motopressCELang->CEwpTags,
                    'category' => $motopressCELang->CEwpTagCloudCategories,
                )
            )
        ), 60);
        /* wp widgets END */

        // WP Audio
         $wpAudioObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_audio', $motopressCELang->CEwpAudio, 'player.png', array(
            'source' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpAudioSourceTitle,
                'description' => $motopressCELang->CEwpAudioSourceDesc,
                'list' => array(
                    'library' => $motopressCELang->CEwpAudioSourceLibrary,
                    'external' => $motopressCELang->CEwpAudioSourceURL,
                ),
                'default' => 'external'
            ),
            'id' => array(
                'type' => 'audio',
                'label' => $motopressCELang->CEwpAudioIdTitle,
                'description' => $motopressCELang->CEwpAudioIdDescription,
                'default' => '',
                'dependency' => array(
                    'parameter' => 'source',
                    'value' => 'library'
                )
                ),
            'url' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEwpAudioUrlTitle,
                'description' => $motopressCELang->CEwpAudioUrlDescription,
                'default' => 'http://wpcom.files.wordpress.com/2007/01/mattmullenweg-interview.mp3',
                'dependency' => array(
                    'parameter' => 'source',
                    'value' => 'external'
                )
            ),
            'autoplay' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpAudioAutoplayTitle,
                'description' => $motopressCELang->CEwpAudioAutoplayDesc,
                'default' => '',
            ),
            'loop' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEwpAudioLoopTitle,
                'description' => $motopressCELang->CEwpAudioLoopDesc,
                'default' => '',
            )
        ), 20, MPCEObject::ENCLOSED);

        // WP Widgets Area
        global $wp_registered_sidebars;
        $wpWidgetsArea_array = array();
        $wpWidgetsArea_default = '';
        if ( $wp_registered_sidebars ){
            foreach ( $wp_registered_sidebars as $sidebar ) {
                if (empty($wpWidgetsArea_default))
                        $wpWidgetsArea_default = $sidebar['id'];
                $wpWidgetsArea_array[$sidebar['id']] = $sidebar['name'];
            }
        }else {
            $wpWidgetsArea_array['no'] = $motopressCELang->CEwpWidgetsAreaNoSidebars;
        }
        $wpWidgetsAreaObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_widgets_area', $motopressCELang->CEwpWidgetsArea, 'sidebar.png', array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEParametersTitle,
                'default' => '',
                'description' => $motopressCELang->CEwpWidgetsAreaDescription
            ),
            'sidebar' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEwpWidgetsAreaSelect,
                'default' => $wpWidgetsArea_default,
                'description' => '',
                'list' => $wpWidgetsArea_array
            )
        ), 5);

        $tabsObj = new MPCEObject(MPCEShortcode::PREFIX . 'tabs', $motopressCELang->CETabsObjName, 'tabs.png', array(
            'tabs' => array(
                'type' => 'group',
                'items' => array(
                    'label' => array(
                        'default' => $motopressCELang->CETabObjTitleLabel,
                        'parameter' => 'title'
                    ),
                    'count' => 2
                ),
                'text' => strtr($motopressCELang->CEAddNewItem, array('%name%' => $motopressCELang->CETabObjName)),
                'disabled' => 'true'
            ),
            'padding' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CETabsObjPaddingLabel,
                'default' => 20,
                'min' => 0,
                'max' => 50,
                'step' => 10
            )
/*
            'color' => array(
                'type' => 'color-picker',
                'label' => $motopressCELang->CEColor,
                'default' => ''
            ),
            'spinner' => array(
                'type' => 'spinner',
                'label' => 'spinner',
                'description' => "desc <a href='http://google.ru' target='_blank'>link</a> <i>foo</i> <b>bar</b>",
                'default' => 50,
                'min' => 0,
                'max' => 100,
                'step' => 10
            ),
            'slider' => array(
                'type' => 'slider',
                'label' => 'Slider',
                'default' => 500,
                'description' => 'Description',
                'min' => -101,
                'max' => 999,
                'step' => 1
            ),
            'buttonsgroup' => array(
                'type' => 'radio-buttons',
                'label' => 'Toggle button group',
                'default' => '#00ff00',
                'list' => array(
                    '#ff0000' => 'Red',
                    '#00ff00' => 'Green',
                    '#0000ff' => 'Blue',
                    '#000000' => 'Black',
                    '#f32222' => 'Red 2',
                    '#22f322' => 'Green 2',
                    '#2222f3' => 'Blue 2',
                    '#cccccc' => 'Gray'
                )
            )

            'layout' => array(
                'type' => 'select',
                'label' => 'layout',
                'default' => 'top-left',
                'list' => array(
                    'top-left' => 'top left'
                )
            ),
            'color' => array(
                'type' => 'select',
                'label' => 'color',
                'default' => 'gray',
                'list' => array(
                    'left' => 'gray'
                )
            )
*/
        ), 20, MPCEObject::ENCLOSED);

        $tabObj = new MPCEObject(MPCEShortcode::PREFIX . 'tab', $motopressCELang->CETabObjName, null, array(
            'id' => array(
                'type' => 'text-hidden'
            ),
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CETabObjTitleLabel,
                'default' => $motopressCELang->CETabObjTitleLabel
            ),
            'content' => array(
                'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CETabObjContentLabel,
                'default' => $motopressCELang->CETabObjContentDefault,
                'text' => $motopressCELang->CEOpenInWPEditor,
                'saveInContent' => 'true'
            ),
            'active' => array(
                'type' => 'group-checkbox',
                'label' => $motopressCELang->CETabObjActiveLabel,
                'default' => 'false',
                'description' => $motopressCELang->CETabObjActiveDesc
            )
        ), null, MPCEObject::ENCLOSED, null, false);

        $accordionObj = new MPCEObject(MPCEShortcode::PREFIX . 'accordion', $motopressCELang->CEAccordionObjName, 'accordion.png', array(
            'accordionItems' => array(
                'type' => 'group',
                'items' => array(
                    'label' => array(
                        'default' => $motopressCELang->CEAccordionItemObjTitleLabel,
                        'parameter' => 'title'
                    ),
                    'count' => 2
                ),
                'text' => strtr($motopressCELang->CEAddNewItem, array('%name%' => $motopressCELang->CEAccordionObjName)),
                'disabled' => 'true'
            ),
            'style' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEObjStyleLabel,
                'default' => 'light',
                'list' => array(
                    'light' => $motopressCELang->CEAccordionObjStyleListLight,
                    'dark' => $motopressCELang->CEAccordionObjStyleListDark
                ),
                'disabled' => 'true'
            )
        ), null, MPCEObject::ENCLOSED);

        $accordionItemObj = new MPCEObject(MPCEShortcode::PREFIX . 'accordion_item', $motopressCELang->CEAccordionItemObjName, null, array(
            'title' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEAccordionItemObjTitleLabel,
                'default' => $motopressCELang->CEAccordionItemObjTitleLabel
            ),
            'content' => array(
                'type' => 'longtext-tinymce',
                'label' => $motopressCELang->CEAccordionItemObjContentLabel,
                'default' => $motopressCELang->CEAccordionItemObjContentDefault,
                'text' => $motopressCELang->CEOpenInWPEditor,
                'saveInContent' => 'true'
            ),
            'active' => array(
                'type' => 'group-checkbox',
                'label' => $motopressCELang->CEAccordionItemObjActiveLabel,
                'default' => 'false',
                'description' => $motopressCELang->CEAccordionItemObjActiveDesc
            )
        ), null, MPCEObject::ENCLOSED, null, false);


        $postsGridObj = new MPCEObject(MPCEShortcode::PREFIX . 'posts_grid', $motopressCELang->CEPostsGridObjName, 'posts-grid.png', array(
            'post_type' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsGridObjPostTypeLabel,
                'description' => $motopressCELang->CEPostsGridObjPostTypeDesc,
                'list' =>MPCEShortcode::getPostTypes()
            ),
            'columns' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjColumnsLabel,
                'default' => 1,
                'list' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    6 => 6
                )
            ),
            'posts_per_page' => array(
                'type' => 'spinner',
                'label' => $motopressCELang->CEPostsGridObjPostsPerPageLabel,
                'default' => 3,
                'min' => 1,
                'max' => 40,
                'step' => 1,
                'disabled' => 'true'
            ),
            'posts_order' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjSortOrder,
                'default' => 'DESC',
                'list' => array(
                    'ASC' => $motopressCELang->CEPostsGridObjSortOrderAscending,
                    'DESC' => $motopressCELang->CEPostsGridObjSortOrderDescending
                ),
                'disabled' => 'true'
            ),
            'template' => array(
                'type' => 'select',
                'label' => $motopressCELang->CEPostsGridObjTemplateLabel,
                'list' => MPCEShortcode::getPostsGridTemplatesList(),
            ),
            'posts_gap' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEPostsGridObjPostsGapLabel,
                'default' => 30,
                'min' => 0,
                'max' => 100,
                'step' => 10,
            ),
            'show_featured_image' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEPostsGridObjShowFeaturedImage,
                'default' => 'true',
            ),
            'title_tag' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjTitleTag,
                'default' => 'h2',
                'list' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'hide' => $motopressCELang->CEPostsGridObjTitleTagNone,
                )
            ),
            'show_date_comments' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEPostsGridObjShowDateComments,
                'default' => 'true',
            ),
            'show_content' => array(
                'type' => 'radio-buttons',
                'label' => $motopressCELang->CEPostsGridObjShowContent,
                'default' => 'short',
                'list' => array(
                    'short' => $motopressCELang->CEPostsGridObjShowContentShort,
                    'full' => $motopressCELang->CEPostsGridObjShowContentFull,
                    'excerpt' => $motopressCELang->CEPostsGridObjShowContentExcerpt,
                    'hide' => $motopressCELang->CEPostsGridObjShowContentNone,
                )
            ),
            'short_content_length' => array(
                'type' => 'slider',
                'label' => $motopressCELang->CEPostsGridObjShortContentLength,
                'default' => 200,
                'min' => 0,
                'max' => 1000,
                'step' => 20,
                'dependency' => array(
                    'parameter' => 'show_content',
                    'value' => 'short'
                ),
            ),
            'read_more_text' => array(
                'type' => 'text',
                'label' => $motopressCELang->CEPostsGridObjReadMoreTextLabel,
                'default' => $motopressCELang->CEPostsGridObjReadMoreText
            ),
            'pagination' => array(
                'type' => 'checkbox',
                'label' => $motopressCELang->CEPostsGridObjShowPagination,
                'default' => 'false'
            )
        ), null);

        /* Groups */
        $gridGroup = new MPCEGroup();
        $gridGroup->setId('grid');
        $gridGroup->setName($motopressCELang->CEGridGroupName);
        $gridGroup->addObject(array($rowObj, $rowInnerObj, $spanObj, $spanInnerObj));

        $textGroup = new MPCEGroup();
        $textGroup->setId('text');
        $textGroup->setName($motopressCELang->CETextGroupName);
        $textGroup->setIcon('text.png');
        $textGroup->setPosition(0);
        $textGroup->addObject(array($textObj, $headingObj, $codeObj, $quotesObj, $membersObj));

        $imageGroup = new MPCEGroup();
        $imageGroup->setId('image');
        $imageGroup->setName($motopressCELang->CEImageGroupName);
        $imageGroup->setIcon('image.png');
        $imageGroup->setPosition(1);
        $imageGroup->addObject(array($imageObj, $imageSlider));

        $buttonGroup = new MPCEGroup();
        $buttonGroup->setId('button');
        $buttonGroup->setName($motopressCELang->CEButtonGroupName);
        $buttonGroup->setIcon('button.png');
        $buttonGroup->setPosition(2);
        $buttonGroup->addObject(array($buttonObj, $socialsObj));

        $mediaGroup = new MPCEGroup();
        $mediaGroup->setId('media');
        $mediaGroup->setName($motopressCELang->CEMediaGroupName);
        $mediaGroup->setIcon('media.png');
        $mediaGroup->setPosition(3);
        $mediaGroup->addObject(array($videoObj, $wpAudioObj));

        $otherGroup = new MPCEGroup();
        $otherGroup->setId('other');
        $otherGroup->setName($motopressCELang->CEOtherGroupName);
        $otherGroup->setIcon('other.png');
        $otherGroup->setPosition(4);
        $otherGroup->addObject(array($gMapObj, $spaceObj, $embedObj, $googleChartsObj, $tabsObj, $tabObj, $accordionObj, $accordionItemObj, $tableObj, $postsGridObj));

        $wordpressGroup = new MPCEGroup();
        $wordpressGroup->setId('wordpress');
        $wordpressGroup->setName($motopressCELang->CEWordPressGroupName);
        $wordpressGroup->setIcon('wordpress.png');
        $wordpressGroup->setPosition(5);
        $wordpressGroup->addObject(array($wpArchiveObj, $wpCalendarObj, $wpCategoriesObj, $wpCustomMenuObj, $wpMetaObj, $wpPagesObj, $wpPostsObj, $wpRecentCommentsObj, $wpRSSObj, $wpSearchObj, $wpTagCloudObj, $wpWidgetsAreaObj));

        $this->addGroup(array($gridGroup, $textGroup, $imageGroup, $buttonGroup, $mediaGroup, $otherGroup, $wordpressGroup));
        $this->setSkippedGroups(array($gridGroup));
    }

    /**
     * @return MPCEGroup[]
     */
    public function getLibrary() {
        return $this->library;
    }

    /**
     * @param string $id
     * @return MPCEGroup|boolean
     */
    public function &getGroup($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->library)) {
                        return $this->library[$id];
                    }
                }
            }
        }
        $group = false;
        return $group;
    }

    /**
     * @param MPCEGroup|MPCEGroup[] $group
     */
    private function addGroup($group) {
        if ($group instanceof MPCEGroup) {
            if ($group->isValid()) {
                if (!array_key_exists($group->getId(), $this->library)) {
                    if (count($group->getObjects()) > 0) {
                        $this->library[$group->getId()] = $group;
                    }
                }
            } else {
                if (!self::$isAjaxRequest) {
                    $group->showErrors();
                }
            }
        } elseif (is_array($group)) {
            if (!empty($group)) {
                foreach ($group as $g) {
                    if ($g instanceof MPCEGroup) {
                        if ($g->isValid()) {
                            if (!array_key_exists($g->getId(), $this->library)) {
                                if (count($g->getObjects()) > 0) {
                                    $this->library[$g->getId()] = $g;
                                }
                            }
                        } else {
                            if (!self::$isAjaxRequest) {
                                $g->showErrors();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function removeGroup($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->library)) {
                        unset($this->library[$id]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return MPCEGroup[]
     */
    public function getSkippedGroups() {
        return $this->skippedGroups;
    }

    /**
     * @param MPCEGroup[] $skippedGroups
     */
    private function setSkippedGroups(array $skippedGroups) {
        if (!empty($skippedGroups)) {
            foreach ($skippedGroups as $skippedGroup) {
                if ($skippedGroup instanceof MPCEGroup) {
                    if ($skippedGroup->isValid()) {
                        $this->skippedGroups[] = $skippedGroup;
                    }
                }
            }
        }
    }

    /**
     * @param MPCEObject|MPCEObject[] $object
     * @param string $group [optional]
     */
    public function addObject($object, $group = MPCEGroup::DEFAULT_GROUP) {
        $groupObj = &$this->getGroup($group);
        if (!$groupObj) {
            $groupObj = &$this->getGroup(MPCEGroup::DEFAULT_GROUP);
        }
        if ($groupObj) {
            $groupObj->addObject($object);
        }
    }

    /**
     * @param string $id
     */
    public function removeObject($id) {
        foreach ($this->library as $group) {
            if (!in_array($group, $this->skippedGroups)) {
                if ($group->removeObject($id)) {
                    break;
                }
            }
        }
    }

    /**
     * @param string $id
     * @return MPCEObject|boolean
     */
    public function &getObject($id) {
        foreach ($this->library as $group) {
            if (!in_array($group, $this->skippedGroups)) {
                $object = &$group->getObject($id);
                if ($object) {
                    return $object;
                }
            }
        }
        $object = false;
        return $object;
    }

    /**
     * @return string|boolean
     */
    public function toJson() {
        $library = array();
        foreach ($this->library as $group) {
            if (!in_array($group, $this->skippedGroups)) {
                if (count($group->getObjects()) > 0) {
                    uasort($group->objects, array(__CLASS__, 'positionCmp'));
                    $library[$group->getId()] = $group;
                }
            }
        }
        uasort($library, array(__CLASS__, 'positionCmp'));
        return json_encode($library);
    }

    /**
     * @return array
     */
    public function getObjectsList() {
        $list = array();
        foreach ($this->library as $group){
            foreach ($group->getObjects() as $object) {
                $parameters = $object->getParameters();
                if (!empty($parameters)) {
                    foreach ($parameters as $key => $value) {
                        unset($parameters[$key]);
                        $parameters[$key] = array();
                    }
                }

                $list[$object->getId()] = array(
                    'parameters' => $parameters,
                    'group' => $group->getId()
                );
            }
        }
        return $list;
    }


    /**
     * @return array
     */
    public function getObjectsNames() {
        $names = array();
        foreach ($this->library as $group){
            foreach ($group->getObjects() as $object){
                $names[] = $object->getId();
            }
        }
        return $names;
    }

    /**
     * @static
     * @param MPCEObject $a
     * @param MPCEObject $b
     * @return int
     */
    /*
    public static function nameCmp(MPCEObject $a, MPCEObject $b) {
        return strcmp($a->getName(), $b->getName());
    }
    */

    /**
     * @param MPCEElement $a
     * @param MPCEElement $b
     * @return int
     */
    public function positionCmp(MPCEElement $a, MPCEElement $b) {
        $aPosition = $a->getPosition();
        $bPosition = $b->getPosition();
        if ($aPosition == $bPosition) {
            return 0;
        }
        return ($aPosition < $bPosition) ? -1 : 1;
    }

    /**
     * @return boolean
     */
    private function isAjaxRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ? true : false;
    }
}
