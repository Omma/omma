<?php
$manager = $this->getContainer()->get('h4cc_alice_fixtures.manager');

$set = $manager->createFixtureSet();
$set->addFile(__DIR__ . '/users.yml', 'yaml');
$set->addFile(__DIR__ . '/meetings.yml', 'yaml');
$set->addFile(__DIR__ . '/attendees.yml', 'yaml');

return $set;
