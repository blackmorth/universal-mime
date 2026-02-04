<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Parser;

use PHPUnit\Framework\TestCase;
use UniversalMime\Parser\Stream\MemoryStream;
use UniversalMime\Model\ContentType;
use UniversalMime\Model\Parameter;
use UniversalMime\UniversalMime;
use UniversalMime\Wire\Line;
use UniversalMime\Parser\Stream\ResourceStream;

final class MimeParserTest extends TestCase
{
    public function testParseSimpleMultipart(): void
    {
        $ctype = new ContentType(
            'multipart',
            'mixed',
            null,
            [new Parameter('boundary', 'abc')]
        );

        $raw =
            "--abc" . Line::CRLF .
            "X-A: 1" . Line::CRLF .
            Line::CRLF .
            "BODY1" . Line::CRLF .
            "--abc" . Line::CRLF .
            "X-B: 2" . Line::CRLF .
            Line::CRLF .
            "BODY2" . Line::CRLF .
            "--abc--" . Line::CRLF;

        $stream = new MemoryStream();
        $stream->write($raw);
        $stream->rewind();

        $parser = UniversalMime::defaultParser();
        $parts = $parser->parse($ctype, $stream);

        $this->assertCount(2, $parts);
        $this->assertSame("1", $parts[0]->headers->get("X-A")->value);
        $this->assertSame("BODY1" . Line::CRLF, $parts[0]->body->stream->read(999));
    }

    public function testMimeParserDecodesBase64(): void
    {
        $raw =
            "--abc" . Line::CRLF .
            "Content-Transfer-Encoding: base64" . Line::CRLF .
            Line::CRLF .
            base64_encode("HELLO") . Line::CRLF .
            "--abc--" . Line::CRLF;

        $ctype = new ContentType('multipart', 'mixed', null, [
            new Parameter('boundary', 'abc')
        ]);

        $parser = UniversalMime::defaultParser();
        $stream = new MemoryStream($raw);

        $parts = $parser->parse($ctype, $stream);
        $body = $parts[0]->body->stream->read(999);

        $this->assertSame("HELLO", $body);
    }

    public function testParseMultipartFromResourceStream(): void
    {
        $raw =
            "--abc" . Line::CRLF .
            "X-A: 1" . Line::CRLF .
            Line::CRLF .
            "BODY1" . Line::CRLF .
            "--abc--" . Line::CRLF;

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $raw);
        rewind($handle);

        $stream = new ResourceStream($handle);
        $parser = UniversalMime::defaultParser();

        $ctype = new ContentType('multipart', 'mixed', null, [
            new Parameter('boundary', 'abc')
        ]);

        $parts = $parser->parse($ctype, $stream);

        $this->assertCount(1, $parts);
        $this->assertSame("1", $parts[0]->headers->get("X-A")->value);
        $this->assertSame("BODY1" . Line::CRLF, $parts[0]->body->stream->read(999));
    }
}
