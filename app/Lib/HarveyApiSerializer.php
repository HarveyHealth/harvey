<?php

namespace App\Lib;

use League\Fractal\Serializer\JsonApiSerializer;

class HarveyApiSerializer extends JsonApiSerializer
{
    /**
     * @param array $data
     * @param array $relationships
     *
     * @return array
     */
    protected function fillRelationships($data, $relationships)
    {
        if ($this->isCollection($data)) {
            foreach ($relationships as $key => $relationship) {
                $data = $this->fillRelationshipAsCollection($data, $relationship, $key);
            }
        } else { // Single resource
            foreach ($relationships as $key => $relationship) {
                $data = $this->fillRelationshipAsSingleResource($data, $relationship, $key);
            }
        }

        return $data;
    }

    /**
     * Loops over the relationships of the provided data and formats it
     *
     * @param $data
     * @param $relationship
     * @param $key
     *
     * @return array
     */
    private function fillRelationshipAsCollection($data, $relationship, $key)
    {
        foreach ($relationship as $index => $relationshipData) {
            $data['data'][$index]['relationships'][$key] = $relationshipData;

            if ($this->shouldIncludeLinks()) {
                $data['data'][$index]['relationships'][$key] = array_merge([
                    'links' => [
                        'related' => "{$this->baseUrl}/{$data['data'][$index]['type']}/{$data['data'][$index]['id']}/$key",
                    ],
                ], $data['data'][$index]['relationships'][$key]);
            }
        }

        return $data;
    }

    /**
     * @param $data
     * @param $relationship
     * @param $key
     *
     * @return array
     */
    private function fillRelationshipAsSingleResource($data, $relationship, $key)
    {
        $data['data']['relationships'][$key] = $relationship[0];

        if ($this->shouldIncludeLinks()) {
            $data['data']['relationships'][$key] = array_merge([
                'links' => [
                    'related' => "{$this->baseUrl}/{$data['data']['type']}/{$data['data']['id']}/$key",
                ],
            ], $data['data']['relationships'][$key]);

            return $data;
        }
        return $data;
    }
}