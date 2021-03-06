<?php
declare(strict_types=1);

/**
 * This file is part of martin1982/livebroadcastbundle which is released under MIT.
 * See https://opensource.org/licenses/MIT for full license details.
 */
namespace Martin1982\LiveBroadcastBundle\Tests\Service\StreamOutput;

use Martin1982\LiveBroadcastBundle\Entity\Channel\ChannelUstream;
use Martin1982\LiveBroadcastBundle\Service\StreamOutput\OutputInterface;
use Martin1982\LiveBroadcastBundle\Service\StreamOutput\OutputUstream;
use PHPUnit\Framework\TestCase;

/**
 * Class OutputUstreamTest
 */
class OutputUstreamTest extends TestCase
{
    /**
     * @var ChannelUstream
     */
    private $channelUstream;

    /**
     * Setup a testable Ustream channel.
     */
    public function setUp()
    {
        $this->channelUstream = new ChannelUstream();
        $this->channelUstream->setStreamServer('server');
        $this->channelUstream->setStreamKey('key');
    }

    /**
     * Test if the class implements the OutputInterface
     */
    public function tesImplementsOutputInterface(): void
    {
        $implements = class_implements(OutputUstream::class);
        self::assertTrue(\in_array(OutputInterface::class, $implements, true));
    }

    /**
     * Test the generate output command without a channel
     * @expectedException \Martin1982\LiveBroadcastBundle\Exception\LiveBroadcastOutputException
     */
    public function testGenerateOutputCmdWithoutChannel(): void
    {
        $ustream = new OutputUstream();
        $ustream->generateOutputCmd();
    }

    /**
     * Test the generate output command with an invalid channel
     *
     * @expectedException \Martin1982\LiveBroadcastBundle\Exception\LiveBroadcastOutputException
     */
    public function testGenerateOutputCmdWithInvalidChannel(): void
    {
        $ustream = new OutputUstream();
        $channel = new ChannelUstream();
        $ustream->setChannel($channel);

        $ustream->generateOutputCmd();
    }

    /**
     * Test if the Ustream output class generates the correct output command.
     *
     * @throws \Martin1982\LiveBroadcastBundle\Exception\LiveBroadcastOutputException
     */
    public function testGenerateOutputCmd(): void
    {
        $ustream = new OutputUstream();
        $ustream->setChannel($this->channelUstream);
        self::assertEquals(
            $ustream->generateOutputCmd(),
            '-vcodec copy -acodec copy -f flv "rtmp://server/key"'
        );
    }

    /**
     * Test if the channelType is correct for this output
     */
    public function testGetChannelType(): void
    {
        $ustream = new OutputUstream();
        self::assertEquals(ChannelUstream::class, $ustream->getChannelType());
    }
}
