src/
└── UniversalMime/
├── Model/
│   ├── Message.php
│   ├── StartLine.php
│   ├── Part.php
│   ├── Header.php
│   ├── HeaderBag.php
│   ├── HeaderValue.php
│   ├── Parameter.php
│   ├── ContentType.php
│   ├── ContentDisposition.php
│   ├── BoundaryGenerator.php
│   └── Body.php
│
├── Wire/
│   ├── RawHeaderLine.php
│   ├── RawMessage.php
│   └── HeaderCodec.php
│
├── Parser/
│   ├── MessageStreamParser.php
│   ├── StartLineParser.php
│   ├── HeaderParser.php
│   ├── MimeParser.php
│   ├── Stream/
│   │   ├── StreamInterface.php
│   │   ├── ResourceStream.php
│   │   ├── LengthLimitedStream.php
│   │   ├── ChunkedStream.php
│   │   └── MultipartStream.php
│   └── TransferDecoders/
│       ├── Base64StreamDecoder.php
│       ├── QPStreamDecoder.php
│       ├── BinaryStreamDecoder.php
│       ├── GzipStreamDecoder.php
│       └── SevenBitStreamDecoder.php
│
├── Encoding/
│   ├── Transfer/
│   │   ├── TransferEncoderInterface.php
│   │   ├── SevenBitEncoder.php
│   │   ├── EightBitEncoder.php
│   │   ├── Base64Encoder.php
│   │   ├── QPEncoder.php
│   │   ├── BinaryEncoder.php
│   │   └── TransferEncodingSelector.php
│   ├── Rfc2047/
│   │   ├── EncodedWordEncoder.php
│   │   └── EncodedWordDecoder.php
│   ├── Rfc2231/
│   │   ├── ParameterEncoder.php
│   │   └── ParameterDecoder.php
│   └── Charset/
│       └── CharsetConverter.php
│
└── Attributes/
   ├── RFC.php
   ├── TransferEncoding.php
   ├── HeaderRule.php
   └── MimeContext.php
