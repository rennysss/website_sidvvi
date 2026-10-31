<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Metadatos SEO, Open Graph y JSON-LD por página */
class Seo
{
	public $title = '';
	public $description = '';
	public $image = '';
	public $canonical = '';
	public $type = 'website';
	public $robots = 'index,follow';
	protected $jsonld = array();
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->description = setting('seo_description');
		$this->image = base_url('assets/img/og-default.jpg');
		$this->canonical = current_url();
		$this->add_jsonld(array(
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => setting('site_full_name', 'SIDVVI'),
			'alternateName' => setting('site_name', 'SIDVVI'),
			'url' => base_url(),
			'logo' => base_url('assets/img/logo.png'),
			'email' => setting('email'),
			'telephone' => setting('phone'),
		));
	}

	public function set($title, $description = NULL, $image = NULL)
	{
		$this->title = $title;
		if ($description) $this->description = str_limit(strip_tags($description), 158);
		if ($image) $this->image = img_url($image);
		return $this;
	}

	public function add_jsonld(array $data) { $this->jsonld[] = $data; return $this; }

	public function full_title()
	{
		$site = setting('site_name', 'SIDVVI');
		return $this->title ? $this->title.' | '.$site : $site.' · '.setting('site_full_name');
	}

	public function render()
	{
		$t = e($this->full_title()); $d = e($this->description); $i = e($this->image); $u = e($this->canonical);
		$h  = "<title>$t</title>\n";
		$h .= "<meta name=\"robots\" content=\"".e($this->robots)."\">\n";
		$h .= "<meta name=\"description\" content=\"$d\">\n";
		$h .= "<meta name=\"keywords\" content=\"".e(setting('seo_keywords'))."\">\n";
		$h .= "<link rel=\"canonical\" href=\"$u\">\n";
		$h .= "<meta property=\"og:type\" content=\"".e($this->type)."\">\n<meta property=\"og:title\" content=\"$t\">\n";
		$h .= "<meta property=\"og:description\" content=\"$d\">\n<meta property=\"og:image\" content=\"$i\">\n<meta property=\"og:url\" content=\"$u\">\n";
		$h .= "<meta property=\"og:locale\" content=\"es_MX\">\n<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
		foreach ($this->jsonld as $j) {
			$h .= '<script type="application/ld+json">'.json_encode($j, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."</script>\n";
		}
		return $h;
	}
}
