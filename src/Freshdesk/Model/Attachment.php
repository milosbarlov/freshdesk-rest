<?php
namespace Freshdesk\Model;


class Attachment extends Base
{
    const RESPONSE_KEY = 'resource';

    //common mime-types (default used is TXT)
    const MIME_TXT  = 'text/plain';
    const MIME_JPG  = 'image/jpeg';
    const MIME_PNG  = 'image/png';
    const MIME_BMP  = 'image/bmp';
    const MIME_GIF  = 'image/gif';
    //compressed files
    const MIME_ZIP  = 'application/x-compressed';
    const MIME_GZIP = 'application/x-gzip';
    const MIME_TAR  = 'application/tar';
    const MIME_GTAR = 'application/x-gtar';
    //markup (unless you need to, use default XML mime-type)
    const MIME_XML  = 'text/xml';
    const MIME_HTML = 'text/html';
    //office
    const MIME_DOC  = 'application/msword';
    const MIME_XLS  = 'application/excel';
    const MIME_PPT  = 'application/powerpoint';

    /**
     * @var bool
     */
    private static $CfSupported = null;

    /**
     * @var null|\CURLFile
     */
    protected $curlFile = null;

    /**
     * @var string
     */
    protected $mimeType = self::MIME_TXT;

    /**
     * @var \SplFileInfo
     */
    protected $file = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * Override parent behaviour
     * Accept SplFileInfo, CURLFile, string, array or traversable objects
     * @param \CURLFile|\SplFileInfo|string|null $data
     */
    public function __construct($data = null)
    {
        if (static::$CfSupported === null)
            static::$CfSupported = class_exists('\\CURLFile');
        if ($data && static::$CfSupported && $data instanceof \CURLFile)
        {//wrap around CURLFile if needed
            $this->setCurlFile($data);
            $data = null;
        }
        if ($data)
        {//if not string, run parent constructor, else assume filename
            if (!is_string($data) && !$data instanceof \SplFileInfo)
                parent::__construct($data);
            else
                $this->setFile($data);
        }
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $mime
     * @return $this
     */
    public function setMimeType($mime)
    {
        $this->mimeType = $mime;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string|\SplFileInfo $file
     * @return $this
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function setFile($file)
    {
        if (is_string($file))
        {
            if (!file_exists(realpath($file))) {
                throw new \RuntimeException(
                    sprintf(
                        'File %s not found (realpath: %s)',
                        $file,
                        realpath($file)
                    )
                );
            }
            $file = new \SplFileInfo(
                $file
            );
        }
        if (!$file instanceof \SplFileInfo)
        {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s expects a string, or an instance of SplFileInfo',
                    __METHOD__
                )
            );
        }
        $this->file = $file;
        if (static::$CfSupported)
            return $this->createCurlFile($this->file);
        if ($this->name === null)
            $this->setName(
                $this->file->getFilename()
            );
        if ($this->mimeType === null)
            $this->setMimeType(
                $this->guessMimeType(
                    $file
                )
            );
        return $this;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    protected function createCurlFile(\SplFileInfo $fi)
    {
        return $this->setCurlFile(
            new \CURLFile(
                $fi->getRealPath()
            )
        );
    }

    /**
     * @param \SplFileInfo $fi
     * @return string
     */
    protected function guessMimeType(\SplFileInfo $fi)
    {
        $info = finfo_open(\FILEINFO_MIME_TYPE);
        $mime = finfo_file(
            $info,
            $fi->getRealPath()
        );
        finfo_close($info);
        if ($mime)
            return $mime;
        switch (strtolower($fi->getExtension()))
        {
            case 'jpg':
            case 'jpeg':
                return self::MIME_JPG;
            case 'png':
                return self::MIME_PNG;
            case 'bmp':
                return self::MIME_BMP;
            case 'gif':
                return self::MIME_GIF;
            case 'gz':
            case 'zip':
                return self::MIME_ZIP;
            case 'html':
            case 'xml':
            case 'htm':
                return self::MIME_XML;
            case 'tar':
                return self::MIME_TAR;
            case 'gtar':
                return self::MIME_GTAR;
            case 'doc':
            case 'odt':
            case 'docx':
                return self::MIME_DOC;
            case 'xls':
            case 'xlsx':
            case 'ods':
                return self::MIME_XLS;
            case 'ppt':
            case 'pptx':
            case 'odp':
                return self::MIME_PPT;
        }
        return self::MIME_TXT;
    }

    /**
     * @param \CURLFile $cf
     * @return $this
     */
    public function setCurlFile(\CURLFile $cf)
    {
        $this->setMimeType(
                $cf->getMimeType()
            )->setName(
                $cf->getPostFilename()
            );
        $this->curlFile = $cf;
        if ($this->file === null && file_exists(realpath($cf->getFileName()))) {
            $this->setFile(
                $cf->getFileName()
            );
        }
        return $this;
    }

    /**
     * @return \CURLFile|null
     */
    protected function getCurlFile()
    {
        return $this->curlFile;
    }

    /**
     * @return string
     */
    public function toJsonData()
    {
        $array = $this->toArray();
        $resource = $array['resource'];
        $data = array(
            'Content-Disposition: form-data; name="helpdesk_ticket[attachments][][resource]"; filename="' . $resource->postname . '"',
            'Content-Type: '.$resource->mime,
            '',
            file_get_contents($resource->name)
        );
        return implode(
            "\r\n",
            $data
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $cf = $this->getCurlFile();
        if ($cf)
            return array(
                'resource'  => $cf
            );
        return array(
            'resource'  => (object) array(
                    'name'      => $this->getFile()->getRealPath(),
                    'mime'      => $this->getMimeType(),
                    'postname'  => $this->getName()
                )
        );
    }
} 