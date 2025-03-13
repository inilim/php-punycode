<?php

namespace TrueBV;

use Inilim\PunyCode\Punycode;

class PunycodeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test encoding Punycode
     *
     * @param string $decoded Decoded domain
     * @param string $encoded Encoded domain
     * @dataProvider domainNamesProvider
     */
    public function testEncode($decoded, $encoded)
    {
        $Punycode = new Punycode();
        $result = $Punycode->encode($decoded);
        $this->assertEquals($encoded, $result);
    }

    /**
     * Test decoding Punycode
     *
     * @param string $decoded Decoded domain
     * @param string $encoded Encoded domain
     * @dataProvider domainNamesProvider
     */
    public function testDecode($decoded, $encoded)
    {
        $Punycode = new Punycode();
        $result = $Punycode->decode($encoded);
        $this->assertEquals($decoded, $result);
    }

    /**
     * Test encoding Punycode in uppercase
     *
     * @param string $decoded Decoded domain
     * @param string $encoded Encoded domain
     * @dataProvider domainNamesProvider
     */
    public function testEncodeUppercase($decoded, $encoded)
    {
        $Punycode = new Punycode();
        $result = $Punycode->encode(mb_strtoupper($decoded, 'UTF-8'));
        $this->assertEquals($encoded, $result);
    }

    /**
     * Test decoding Punycode in uppercase
     *
     * @param string $decoded Decoded domain
     * @param string $encoded Encoded domain
     * @dataProvider domainNamesProvider
     */
    public function testDecodeUppercase($decoded, $encoded)
    {
        $Punycode = new Punycode();
        $result = $Punycode->decode(strtoupper($encoded));
        $this->assertEquals($decoded, $result);
    }

    /**
     * Provide domain names containing the decoded and encoded names
     *
     * @return array
     */
    public function domainNamesProvider()
    {
        return array(
            // http://en.wikipedia.org/wiki/.test_(international_domain_name)#Test_TLDs
            array(
                'مثال.إختبار',
                'xn--mgbh0fb.xn--kgbechtv',
            ),
            array(
                'مثال.آزمایشی',
                'xn--mgbh0fb.xn--hgbk6aj7f53bba',
            ),
            array(
                '例子.测试',
                'xn--fsqu00a.xn--0zwm56d',
            ),
            array(
                '例子.測試',
                'xn--fsqu00a.xn--g6w251d',
            ),
            array(
                'пример.испытание',
                'xn--e1afmkfd.xn--80akhbyknj4f',
            ),
            array(
                'उदाहरण.परीक्षा',
                'xn--p1b6ci4b4b3a.xn--11b5bs3a9aj6g',
            ),
            array(
                'παράδειγμα.δοκιμή',
                'xn--hxajbheg2az3al.xn--jxalpdlp',
            ),
            array(
                '실례.테스트',
                'xn--9n2bp8q.xn--9t4b11yi5a',
            ),
            array(
                'בײַשפּיל.טעסט',
                'xn--fdbk5d8ap9b8a8d.xn--deba0ad',
            ),
            array(
                '例え.テスト',
                'xn--r8jz45g.xn--zckzah',
            ),
            array(
                'உதாரணம்.பரிட்சை',
                'xn--zkc6cc5bi7f6e.xn--hlcj6aya9esc7a',
            ),

            array(
                'derhausüberwacher.de',
                'xn--derhausberwacher-pzb.de',
            ),
            array(
                'renangonçalves.com',
                'xn--renangonalves-pgb.com',
            ),
            array(
                'рф.ru',
                'xn--p1ai.ru',
            ),
            array(
                'δοκιμή.gr',
                'xn--jxalpdlp.gr',
            ),
            array(
                'ফাহাদ্১৯.বাংলা',
                'xn--65bj6btb5gwimc.xn--54b7fta0cc',
            ),
            array(
                '𐌀𐌖𐌋𐌄𐌑𐌉·𐌌𐌄𐌕𐌄𐌋𐌉𐌑.gr',
                'xn--uba5533kmaba1adkfh6ch2cg.gr',
            ),
            array(
                'guangdong.广东',
                'guangdong.xn--xhq521b',
            ),
            array(
                'gwóźdź.pl',
                'xn--gwd-hna98db.pl',
            ),
            array(
                'άέήίΰαβγδεζηθικλμνξοπρσστυφχ.com',
                'xn--hxacdefghijklmnopqrstuvw0caz0a1a2a.com'
            ),
        );
    }

    /**
     * Test encoding Punycode with invalid domains
     *
     * @param string $decoded Decoded domain
     * @param string $exception
     * @param string $message
     *
     * @dataProvider invalidUtf8DomainNamesProvider
     */
    public function testEncodeInvalid($decoded, $exception, $message)
    {
        $Punycode = new Punycode();
        $ex = null;

        try {
            $Punycode->encode($decoded);
        } catch (\Exception $e) {
            $ex = $e;
        }

        $this->assertNotNull($ex);
        $this->assertInstanceOf($exception, $ex);
        $this->assertEquals($message, $ex->getMessage());
    }

    /**
     * Provide invalid domain names containing the decoded names
     *
     * @return array
     */
    public function invalidUtf8DomainNamesProvider()
    {
        return array(
            array(
                'äöüßáàăâåãąāæćĉčċçďđéèĕêěëėęēğĝġģĥħíìĭîïĩįīıĵķĺľļłńňñņŋóòŏôőõøōœĸŕřŗśŝšşťţŧúùŭûůűũųūŵýŷÿźžżðþ.de',
                '\TrueBV\Exception\LabelOutOfBoundsException',
                'The length of any one label is limited to between 1 and 63 octets, but 167 given.',
            ),
            array(
                'aaaaa.aaaaaaaaaaaaaaa.aaaaaaaaaaaaaaaaaaa.aaaaaaaaaaaaaaaaa.aaaaaaaaaaaaaaaaaa.äöüßáàăâåãąāæćĉčċçďđéèĕêěëėęēğĝġģĥ.ħíìĭîïĩįīıĵķĺľļłńňñņŋóòŏôőõ.øōœĸŕřŗśŝšşťţŧúùŭûůűũųū.ŵýŷÿźžżðþ.de',
                '\TrueBV\Exception\DomainOutOfBoundsException',
                'A full domain name is limited to 255 octets (including the separators), 258 given.',
            ),
            array(
                'aa..aa.de',
                '\TrueBV\Exception\LabelOutOfBoundsException',
                'The length of any one label is limited to between 1 and 63 octets, but 0 given.',
            ),

        );
    }

    /**
     * Test decoding Punycode with invalid domains
     *
     * @param string $encoded Encoded domain
     * @param string $exception
     * @param string $message
     *
     * @dataProvider invalidAsciiDomainNameProvider
     */
    public function testDecodeInvalid($encoded, $exception, $message)
    {
        $Punycode = new Punycode();
        $ex = null;

        try {
            $Punycode->decode($encoded);
        } catch (\Exception $e) {
            $ex = $e;
        }

        $this->assertNotNull($ex);
        $this->assertInstanceOf($exception, $ex);
        $this->assertEquals($message, $ex->getMessage());
    }

    /**
     * Provide invalid domain names containing the encoded names
     *
     * @return array
     */
    public function invalidAsciiDomainNameProvider()
    {
        return array(
            array(
                'xn--zcaccffbljjkknnoorrssuuxxd5e0a0a3ae9c6a4a9bzdzdxdudwdxd2d2d8d0dse7d6dwe9dxeueweye4eyewe9e5ewkkewc9ftfpfplwexfwf4infvf2f6f6f7f8fpg8fmgngrgrgvgzgygxg3gyg1g3g5gykqg9g.de',
                '\TrueBV\Exception\LabelOutOfBoundsException',
                'The length of any one label is limited to between 1 and 63 octets, but 167 given.',
            ),
            array(
                'xn--zcaccffbljjkknnoorrssuuxxd5e0a0a3ae9c8c1b0dxdvdvdxdvd3d0d6dyd8d5d4due7dveseuewe2eweue7e3esk9dxc7frf9e7kuevfuf1ilftf5f4f4f5f6fng6f8f9fpgpgtgxgwgvg1g2gzg1g3gvkog7g.xn--vda.de',
                '\TrueBV\Exception\LabelOutOfBoundsException',
                'The length of any one label is limited to between 1 and 63 octets, but 165 given.',
            ),
            array(
                'aaaaa.aaaaaaaaaaaaaaa.aaaaaaaaaaaaa.aaaaaaaaaaaaaaaa.aaaaaaaaaa.xn--zcaccffbljjkknn6lsd0d4a3b2b2b4b4byc8b0c8b4c0czcwd3c9c8c8c.xn--ddabeekggjj50c0ayw5a5a8d8a6cxb1bzfzb8b7bze8e8b.xn--pdaccf61ajetbrstxy0a1a5a5a9a2b0bzb6b5b8b.xn--hdazec20dnawqr.de',
                '\TrueBV\Exception\DomainOutOfBoundsException',
                'A full domain name is limited to 255 octets (including the separators), 256 given.',
            ),
        );
    }
}
