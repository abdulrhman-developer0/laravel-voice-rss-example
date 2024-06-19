<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;

class VoiceRSS
{
    /**
     * Allowed codecs
     *
     * @var array
     */
    public static $allowedCodecs = [
        'MP3',
        'WAV',
        'AAC',
        'OGG',
        'CAF'
    ];

    /**
     * The api key
     *
     * @var string
     */
    private string $apiKey;

    /**
     * The base url for api
     *
     * @var string
     */
    private string $url;


    /**
     * The target language
     *
     * @var string
     */
    private string  $hl = 'en-us';

    /**
     * The text to speech
     *
     * @var string|null
     */
    private ?string $src = null;

    /**
     * The type of codec
     */
    private string $codec =  'MP3';

    /**
     * The audo format
     *
     * @var string
     */
    private $format = '8khz_8bit_mono';

    /**
     * The speech rate start form -10 upto 10
     *
     * @var int
     */
    private int $rate = 0;

    /**
     * Initialize VoiceRSs instance.
     *
     * @param string $apiKey
     * @param bool $ssl
     */
    public function __construct(string $apiKey, bool $ssl = true)
    {
        $this->apiKey = $apiKey;
        $this->url    = ($ssl ? 'https' : 'http') . '://api.voicerss.org/';
    }

    /**
     * The languate to speech.
     *
     * @param string $hl
     */
    public function language(string $hl): self
    {
        $this->hl = $hl;
        return $this;
    }

    /**
     * The text to speech
     *
     * @param string $text
     */
    public function text(string $text): self
    {
        $this->src = $text;
        return $this;
    }

    /**
     * The audo codect
     *
     * @param string $codec
     */
    public function codec(string $codec): self
    {
        if ( ! in_array($codec, static::$allowedCodecs) ) throw new \Exception("VOICE RSS ERROR: Invalid codec $codec allowed codecs is " . implode('|', static::$allowedCodecs));
        $this->codec = $codec;
        return $this;
    }

    /**
     * The audio format
     *
     * @param string $format
     */
    public function format(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * The speed rate of speech
     *
     * @param int $speed The speed start from -10 upto 10
     */
    public function rate(int $rate): self
    {
        if ( $rate < -10 || $rate > 10) throw new \Exception("rate of speech start from -1 up to 10");
        $this->rate = $rate;
        return $this;
    }

    /**
     * Convert MP
     */
    public function toSpeech()
    {
        if (!$this->src) throw new \Exception("plese use text('hello, world') and passing any string to use this method");

        $response = Http::get($this->url, [
            'key'   => $this->apiKey,
            'hl'    => $this->hl,
            'src'   => $this->src,
            'c'     => $this->codec,
            'f'     => $this->format,
            'r'     => $this->rate
        ]);

        $content = $response->body();
        if ( str_contains($content, 'ERROR:') ) throw new \Exception(
            "VOICE RSS $content"
        );

        return $content;
    }
}
