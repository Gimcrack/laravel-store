<?php

namespace Ingenious\Store;

use Zttp\Zttp;
use Illuminate\Support\Facades\Cache;

abstract class StoreEngineStub
{

    protected $key;

    protected $secret;

    protected $endpoint;

    protected $force_flag = false;

    /**
     * Get the endpoint
     * @method endpoint
     *
     * @return   string
     */
    protected function endpoint()
    {
        return $this->endpoint;
    }


    /**
     * Get the formatted url
     * @method url
     *
     * @param  string $url
     * @return string
     */
    protected function url($url)
    {
        return vsprintf("%s/%s", [
            $this->endpoint,
            trim($url, '/')
        ]);
    }


    /**
     * Get the requested url
     *
     * @param  string $url
     * @return
     */
    protected function get($url)
    {
        return Zttp::withHeaders([
            "Content-Type" => "application/xml",
            "Authorization" => $this->createAuthHeader("GET",$url)
        ])->get($url);
    }


    /**
     * Get the request url and return json
     * @method getJson
     *
     * @param $url
     * @return array
     */
    protected function getJson($url)
    {
        $expanded = $this->url($url);

        if ($this->force_flag) {
            Cache::forget("store.{$expanded}");
        }
        $this->force_flag = false;

        return Cache::remember("store.{$expanded}", 15, function () use ($expanded) {
            return (object) $this->xml_to_json($this->get($expanded)->body());
        });
    }

    /**
     * Create the auth header
     *
     * @param $method
     * @param $url
     * @return string
     */
    function createAuthHeader($method, $url) {
        $time = time()*1000;

        $data = "$method $url $time";
        $sig = sha1("$data {$this->secret}");

        return "Authorization: SprdAuth apiKey=\"{$this->key}\", data=\"{$data}\", sig=\"$sig\"";
    }

    /**
     * Convert the crappy xml to sweet, sweet json
     * @method xml_to_json
     *
     * @param  string $xml
     * @param array $options
     * @return array
     */
    protected function xml_to_json($xml, $options = [])
    {
        if (is_string($xml)) {
            $xml = new \SimpleXMLElement($xml);
        }

        $defaults = [
            'namespaceSeparator' => ':',//you may want this to be something other than a colon
            'attributePrefix' => '',   //to distinguish between attributes and nodes with the same name
            'alwaysArray' => [],   //array of xml tag names which should always become arrays
            'autoArray' => true,        //only create arrays for tags which appear more than once
            'textContent' => '$',       //key used for the text content of elements
            'autoText' => true,         //skip textContent key if node has no attributes or child nodes
            'keySearch' => false,       //optional search and replace on tag and attribute names
            'keyReplace' => false       //replace values for above search values (as passed to str_replace())
        ];
        $options = array_merge($defaults, $options);
        $namespaces = $xml->getDocNamespaces();
        $namespaces[''] = null; //add base (empty) namespace

        //get attributes from all namespaces
        $attributesArray = [];
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
                //replace characters in attribute name
                if ($options['keySearch']) $attributeName =
                    str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
                $attributeKey = $options['attributePrefix']
                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                    . $attributeName;
                $attributesArray[$attributeKey] = (string) $attribute;
            }
        }

        //get child nodes from all namespaces
        $tagsArray = [];
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->children($namespace) as $childTagName => $childXml) {
                //recurse into child nodes
                $childArray = $this->xml_to_json($childXml, $options);
                list($childTagName, $childProperties) = each($childArray);

                //replace characters in tag name
                if ($options['keySearch']) $childTagName =
                    str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
                //add namespace prefix, if any
                if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

                if ( ! isset($tagsArray[$childTagName])) {
                    //only entry with this key
                    //test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] =
                        in_array($childTagName, $options['alwaysArray']) || ! $options['autoArray']
                            ? [$childProperties] : $childProperties;
                } elseif (
                    is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                    === range(0, count($tagsArray[$childTagName]) - 1)
                ) {
                    //key already exists and is integer indexed array
                    $tagsArray[$childTagName][] = $childProperties;
                } else {
                    //key exists so convert to integer indexed array with previous value in position 0
                    $tagsArray[$childTagName] = [$tagsArray[$childTagName], $childProperties];
                }
            }
        }

        //get text content of node
        $textContentArray = [];
        $plainText = trim((string) $xml);
        if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;

        //stick it all together
        $propertiesArray = ! $options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
            ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

        //return node as array
        return array(
            $xml->getName() => $propertiesArray
        );
    }

    /**
     * Force it to update
     * @method force
     *
     * @return   $this
     */
    public function force()
    {
        $this->force_flag = true;

        return $this;
    }
}
