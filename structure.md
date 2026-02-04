src/
├── Attributes/
│   ├── HeaderRule.php
│   ├── MessageContext.php
│   ├── MimeContext.php
│   ├── Protocol.php
│   ├── RFC.php
│   └── TransferEncoding.php
├── Encoding/
│   └── Transfer/
│       ├── Base64StreamDecoder.php
│       ├── GzipStreamDecoder.php
│       ├── IdentityStreamDecoder.php
│       ├── QPStreamDecoder.php
│       ├── TransferDecoderInterface.php
│       ├── TransferDecoderProvider.php
│       └── TransferEncodingRegistry.php
├── Model/
│   ├── Body.php
│   ├── BoundaryGenerator.php
│   ├── ContentDisposition.php
│   ├── ContentType.php
│   ├── Header.php
│   ├── HeaderBag.php
│   ├── HeaderValue.php
│   ├── Message.php
│   ├── Parameter.php
│   ├── Part.php
│   └── StartLine.php
├── Parser/
│   ├── HeaderParser.php
│   ├── HeaderParserInterface.php
│   ├── MessageParserInterface.php
│   ├── MessageStreamParser.php
│   ├── MimeParser.php
│   ├── ParserFactory.php
│   ├── StartLineParser.php
│   ├── StartLineParserInterface.php
│   └── Stream/
│       ├── ChunkedStream.php
│       ├── LengthLimitedStream.php
│       ├── MemoryStream.php
│       ├── MultipartStream.php
│       ├── ResourceStream.php
│       └── StreamInterface.php
├── Wire/
│   ├── Header/
│   │   ├── HeaderCodec.php
│   │   ├── RawHeaderBlock.php
│   │   └── RawHeaderLine.php
│   ├── Message/
│   │   └── RawMessage.php
│   └── Line.php
├── UniversalMime.php
└── .gitkeep
