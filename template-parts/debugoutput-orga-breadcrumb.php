<?php

/**
 * DEBUG-Mode Output für die ORGA Breadcrumb
 *
 * @package    WordPress
 * @subpackage FAU
 * @since      FAU 2.0
 */
?>
<nav class="orga-breadcrumb" aria-label="<?php _e('Organisatorische Navigation', 'fau-orga-breadcrumb'); ?>">
    <ol class="breadcrumblist" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item"
                                                                                           href="https://www.fau.de"><span
                    itemprop="name">Friedrich-Alexander-Universität Erlangen-Nürnberg</span></a>
            <meta itemprop="position" content="1"/>
        </li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item"
                                                                                           href="https://www.tf.fau.de/"><span
                    itemprop="name">Technische Fakultät</span></a>
            <meta itemprop="position" content="2"/>
        </li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item"
                                                                                           href="https://www.informatik.uni-erlangen.de"><span
                    itemprop="name">Department Informatik</span></a>
            <meta itemprop="position" content="3"/>
        </li>
    </ol>
</nav>
