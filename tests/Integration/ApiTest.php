<?php

namespace Tests\Integration;

use App\Event;
use App\EventSection;
use App\Http\Controllers\EventsController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get active markers.
     *
     * @return void
     */
    public function testReadMarkers()
    {
        $response = $this->get('/api/events');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            [
                'event_id',
                'name',
                'date_from',
                'date_to',
                'is_active',
                'address',
                'latitude',
                'longitude',
            ]
        ]);

        $decoded = $response->decodeResponseJson();

        // test seeding has certain number of items. If you change the test data change this test accordingly.
        /** @noinspection PhpParamsInspection */
        $this->assertCount(8, $decoded);
    }

    /**
     * Test get active markers.
     *
     * @return void
     */
    public function testReadMarkersInRegion()
    {
        $params = http_build_query([
            EventsController::PARAM_LAT   => '-33.861034',
            EventsController::PARAM_LONG  => '151.171936',
            EventsController::PARAM_RANGE => 5,
        ]);

        $response = $this->get("/api/events?$params");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            [
                'event_id',
                'name',
                'date_from',
                'date_to',
                'is_active',
                'address',
                'latitude',
                'longitude',
            ]
        ]);

        $decoded = $response->decodeResponseJson();

        // test seeding has certain number of items. If you change the test data change this test accordingly.
        /** @noinspection PhpParamsInspection */
        $this->assertCount(5, $decoded);
    }

    /**
     * Test get event.
     *
     * @return void
     */
    public function testReadEvent()
    {
        $eventId  = 15;
        $response = $this->get("/api/events/$eventId");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'event_id',
            'name',
            'date_from',
            'date_to',
            'is_active',
            'address',
            'latitude',
            'longitude',
            'map_image_base64',
            'event_sections',
        ]);
    }

    /**
     * Test reserve section.
     *
     * @return void
     */
    public function testReserveSection()
    {
        $sectionToReserveId = $this->findFirstNonReservedEventSection()->event_section_id;

        $mockFilePath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Data', 'logo_sample.png']);
        $this->assertTrue(file_exists($mockFilePath));
        $mockFile = UploadedFile::fake()->createWithContent('logo_sample.png', file_get_contents($mockFilePath));

        $inputs   = [
            'company_name'        => 'Company Name',
            'company_logo_base64' => 'currently not validated',
            'contact_name'        => 'John Doe',
            'contact_phone'       => '+11234567890',
            'contact_email'       => 'boo@mail.foo',
        ];

        // emulate POST with attachments (native `post` cant work with attachments)
        $server   = $this->transformHeadersToServerVars([]);
        $cookies  = $this->prepareCookiesForRequest();
        $response = $this->call(
            'POST',
            "/api/event-sections/$sectionToReserveId",
            $inputs,
            $cookies,
            ['company_logo' => $mockFile],
            $server
        );

        $response->assertStatus(200);

        // check it is reserved
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var EventSection $section */
        $section = EventSection::find($sectionToReserveId);

        $this->assertEquals('Company Name', $section->company_name);
    }

    /**
     * @return EventSection
     */
    private function findFirstNonReservedEventSection(): EventSection
    {
        // first existing event
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var Event $event */
        $event = Event::active()->firstOrFail();

        $found = null;
        foreach ($event->eventSections as $section) {
            if (empty($section->company_name) === true) {
                $found = $section;
                break;
            }
        }

        $this->assertNotNull($found);

        return $found;
    }
}
