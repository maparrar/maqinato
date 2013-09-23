<?php
/** FileServer File
* @package core @subpackage  */
/**
* FileServer Class
*
* @author https://github.com/maparrar/maqinato
* @author maparrar <maparrar@gmail.com>
* @package core
* @subpackage 
*/
class FileServer{
    /**
     * Define si se debe acceder a los archivos de la aplicación en un folder o
     * una URL extarna
     * @var mixed:
     *      false: No carga archivos para la aplicación
     *      "local": Lee los datos de un folder dentro de la aplicación
     *      "external": Lee los datos de una fuente externa por medio de una URL
     */
    protected $source;
    /**
     * Define si se accede por SSL a los archivos externos
     * @var bool true para acceso seguro al servidor de archivos (debe estar 
     *           configurado en el servidor), false en otro caso
     */
    protected $isSSL;
    /**
     * Dominio del servidor de archivos para acceder a los datos, no incluye el 
     * protocolo, pues se define en la variable isSSL. No se usa en caso de 
     * source="local"
     * @var string Dominio en caso de source="external", por ejemplo:
     *      - "www.maqinato.com"
     *      - "s3.amazonaws.com"
     */
    protected $domain;
    /**
     * Bucket o contenedor, usado principalmente en servidores de datos externos
     * como AWS. No se usa en caso de source="local"
     * @var string Contenedor de archivos en caso de datos externos como AWS
     */
    protected $bucket;
    /**
     * Folder raíz de almacenamiento
     * @var string:
     *      - En caso de que source="local" debe ser una ruta relativa dentro
     *        del folder de la aplicación. P.e.
     *          - si la aplicación está en la ruta: "/var/www/maqinato" y el folder 
     *            de datos en "/var/www/maqinato/data/" se debe pasar a esta 
     *            variable el valor "data/"
     *          - si la aplicación está en la ruta: "/var/www/maqinato" y el folder 
     *            de datos en "/var/www/maqinato/foo/data" se debe pasar a esta 
     *            variable el valor "foo/data/"
     *      - En caso de source="external", debe ser el folder que contiene los
     *        datos. P.e.
     *          - si los datos están almacenados en "http://dataserver.com/foo/data"
     *            el valor de esta variable debe ser: "foo/data/"
     *          - si se trata de un proveedor de datos externos como AWS que requiere
     *            un bucket o contenedor, se especifica en otra variable, excluyendo
     *            en esta variable el nombre del bucket. Para un servidor
     *            "http://s3.amazonaws.com/bucket_name/data" el valor de esta
     *            variable debe ser: "data/".
     */
    protected $folder;
    /**
     * Clave de acceso al servidor de archivos, por ahora solo se usa con servidores
     * AWS.
     * @var string Clave de acceso al servidor de archivos
     */
    protected $accessKey;
    /**
     * Clave secreta para acceso al servidor de archivos. Solo usada para AWS.
     * @var string Clave secreta para aceeder al servidor de archivos
     */
    protected $secretKey;
    /**
    * Constructor
    * @param string $source Tipo de acceso al servidor de archivos        
    * @param bool $isSSL Si se debe usar SSL        
    * @param string $domain Dominio del servidor de archivos        
    * @param string $bucket Contenedor de archivos en el servidor        
    * @param string $folder Folder de los archivos        
    * @param string $accessKey Clave de acceso al servidor de archivos        
    * @param string $secretKey Clave secreta de acceso al servidor de archivos        
    */
    function __construct($source="",$isSSL=false,$domain="",$bucket="",$folder="",$accessKey="",$secretKey=""){        
        $this->source=$source;
        $this->isSSL=$isSSL;
        $this->domain=$domain;
        $this->bucket=$bucket;
        $this->folder=$folder;
        $this->accessKey=$accessKey;
        $this->secretKey=$secretKey;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
    * Setter source
    * @param string $value Tipo de acceso al servidor de archivos
    * @return void
    */
    public function setSource($value) {
        $this->source=$value;
    }
    /**
    * Setter isSSL
    * @param bool $value Si se debe usar SSL
    * @return void
    */
    public function setIsSSL($value) {
        $this->isSSL=$value;
    }
    /**
    * Setter domain
    * @param string $value Dominio del servidor de archivos
    * @return void
    */
    public function setDomain($value) {
        $this->domain=$value;
    }
    /**
    * Setter bucket
    * @param string $value Contenedor de archivos en el servidor
    * @return void
    */
    public function setBucket($value) {
        $this->bucket=$value;
    }
    /**
    * Setter folder
    * @param string $value Folder de los archivos
    * @return void
    */
    public function setFolder($value) {
        $this->folder=$value;
    }
    /**
    * Setter accessKey
    * @param string $value Clave de acceso al servidor de archivos
    * @return void
    */
    public function setAccessKey($value) {
        $this->accessKey=$value;
    }
    /**
    * Setter secretKey
    * @param string $value Clave secreta de acceso al servidor de archivos
    * @return void
    */
    public function setSecretKey($value) {
        $this->secretKey=$value;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
    * Getter: source
    * @return string
    */
    public function getSource() {
        return $this->source;
    }
    /**
    * Getter: isSSL
    * @return bool
    */
    public function getIsSSL() {
        return $this->isSSL;
    }
    /**
    * Getter: domain
    * @return string
    */
    public function getDomain() {
        return $this->domain;
    }
    /**
    * Getter: bucket
    * @return string
    */
    public function getBucket() {
        return $this->bucket;
    }
    /**
    * Getter: folder
    * @return string
    */
    public function getFolder() {
        return $this->folder;
    }
    /**
    * Getter: accessKey
    * @return string
    */
    public function getAccessKey() {
        return $this->accessKey;
    }
    /**
    * Getter: secretKey
    * @return string
    */
    public function getSecretKey() {
        return $this->secretKey;
    }    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}