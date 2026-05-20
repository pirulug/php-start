<?php

/**
 * Renderiza todas las etiquetas meta y el título del sitio para SEO de forma dinámica.
 *
 * @param string $default_robots Directiva de robots por defecto ("index, follow" o "noindex, nofollow").
 * @return void
 */
function render_seo_meta($default_robots = "index, follow") {
  global $config;

  $seo_title = get_block("meta_title");
  if (empty($seo_title)) {
    $page_title = get_block("title");
    $seo_title = $config->title($page_title);
  }

  $seo_description = get_block("meta_description", $config->siteDescription());
  $seo_keywords = get_block("meta_keywords", $config->siteKeywords());
  $seo_robots = get_block("meta_robots", $default_robots);

  $canonical_url = get_block("canonical");
  if (empty($canonical_url)) {
    $canonical_url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  }

  $og_image = get_block("og_image");
  if (empty($og_image) && $config->ogImage()) {
    $og_image = APP_URL . "/storage/uploads/site/" . $config->ogImage();
  }

  $html = "<title>" . clear_html($seo_title) . "</title>\n";
  $html .= "  <meta name=\"description\" content=\"" . clear_html($seo_description) . "\">\n";
  if (!empty($seo_keywords)) {
    $html .= "  <meta name=\"keywords\" content=\"" . clear_html($seo_keywords) . "\">\n";
  }
  $html .= "  <meta name=\"robots\" content=\"" . clear_html($seo_robots) . "\">\n";
  $html .= "  <link rel=\"canonical\" href=\"" . clear_html($canonical_url) . "\">\n\n";

  $html .= "  <!-- Open Graph / Facebook -->\n";
  $html .= "  <meta property=\"og:site_name\" content=\"" . clear_html($config->siteName()) . "\">\n";
  $html .= "  <meta property=\"og:title\" content=\"" . clear_html($seo_title) . "\">\n";
  $html .= "  <meta property=\"og:description\" content=\"" . clear_html($seo_description) . "\">\n";
  $html .= "  <meta property=\"og:url\" content=\"" . clear_html($canonical_url) . "\">\n";
  $html .= "  <meta property=\"og:type\" content=\"" . clear_html(get_block("og_type", "website")) . "\">\n";
  if (!empty($og_image)) {
    $html .= "  <meta property=\"og:image\" content=\"" . clear_html($og_image) . "\">\n";
  }

  $html .= "\n  <!-- Twitter -->\n";
  $html .= "  <meta name=\"twitter:card\" content=\"" . clear_html(get_block("twitter_card", "summary_large_image")) . "\">\n";
  $html .= "  <meta name=\"twitter:title\" content=\"" . clear_html($seo_title) . "\">\n";
  $html .= "  <meta name=\"twitter:description\" content=\"" . clear_html($seo_description) . "\">\n";
  if (!empty($og_image)) {
    $html .= "  <meta name=\"twitter:image\" content=\"" . clear_html($og_image) . "\">\n";
  }

  echo $html;
}
