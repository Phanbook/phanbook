<?php
/**
 * Phanbook : Delightfully simple forum software
 *
 * Licensed under The GNU License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
namespace Phanbook\Utils;

use Phanbook\Models\Posts;
use Phanbook\Models\Users;
use Phanbook\Tools\ZFunction;
use Phalcon\Config\Adapter\Php as AdapterPhp;
use Phanbook\Utils\Slug;

/**
 *
 */
class Phanbook
{
    /**
     * Themeing name
     *
     * @var string
     */
    protected $theme = 'default';

    /**
     * This is config file get from info.php of theme inside directory content
     *
     * @var array
     */
    protected $config = [];

    public function __construct($config)
    {
        $this->config = $config;
        $this->theme  = $config['code'];
    }
    /**
     * It will use for render url in tag link, script
     *
     * @param  string $item such as css/app.css
     * @return string
     */
    public function assetContent($item)
    {
        return '/content/themes/'. $this->theme .'/' . $item;
    }

    /**
     * Retrieve a page give its title
     *
     * @param  string $title Page title
     *
     * @return object on success or null on failure
     */
    public function getPageByTitle($title)
    {
        $param = [
            'type = "pages" AND title =?0',
            'bind' =>[$title]
        ];
        $post = Posts::findFirst($param);
        if (!$post) {
            return null;
        }
        return $post;
    }
    public function getPostBySlug($slug)
    {
        $post = Posts::findFirstBySlug($slug);
        if (!$post) {
            return null;
        }
        return $post;
    }
    /**
     * Retrieves the directory name of the current theme, without the trailing slash.
     *
     * @return string
     */
    public function getTemplate()
    {
        return ROOT_DIR . '/content/themes/' . $this->theme;
    }
    /**
     * Retrieves the file name of the current theme with url.
     * /about it will return such as content/themes/default/pages/about.volt
     *
     * @return string
     */
    public function getPageFile($name)
    {
        return ROOT_DIR . '/content/themes/' . $this->theme . '/pages/'. $name . '.volt';
    }

    public function saveConfig($arrayConfig)
    {
        $filename = ROOT_DIR . '/content/options/options.php';
        if (!file_exists($filename)) {
            $makeFile = ZFunction::makeFile($filename);
            file_put_contents($filename, "<?php return [];");
        }
        if (file_exists($filename)) {
            $data   = new AdapterPhp($filename);
            $result = array_merge($data->toArray(), $arrayConfig);
            $result ='<?php return ' . var_export($result, true) . ';';

            if (!file_put_contents($filename, $result)) {
                return false;
            }
            return true;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return Users::findFirstById($id);
    }

    /**
     * @return \Phanbook\Utils\Slug
     */
    public function slug()
    {
        return new Slug();
    }

    /**
     * @return Tag
     */
    public function tag()
    {
        return new Tag();
    }
}
