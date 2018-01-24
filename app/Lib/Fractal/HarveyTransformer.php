<?php

namespace App\Lib\Fractal;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\{Collection, Model};
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;
use ReflectionClass;

class HarveyTransformer extends TransformerAbstract
{
    public function processIncludedResources(Scope $scope, $data)
    {
        $includedData = [];

        $scope->getResource()->setTransformer($this);

        $includes = $this->figureOutWhichIncludes($scope);

        foreach ($includes as $include) {
            $includedData = $this->includeResourceIfAvailableAndPolicyAllowsInclusion(
                $scope,
                $data,
                $includedData,
                $include
            );
        }

        return $includedData === [] ? false : $includedData;
    }

    /**
     * Figure out which includes we need.
     *
     * @internal
     *
     * @param Scope $scope
     *
     * @return array
     */
    private function figureOutWhichIncludes(Scope $scope) : array
    {
        $includes = $this->getDefaultIncludes();

        foreach ($this->getAvailableIncludes() as $include) {
            if ($scope->isRequested($include)) {
                $includes[] = $include;
            }
        }

        foreach ($includes as $include) {
            if ($scope->isExcluded($include)) {
                $includes = array_diff($includes, [$include]);
            }
        }

        return $includes;
    }

    private function includeResourceIfAvailableAndPolicyAllowsInclusion(Scope $scope, Model $data, array $includedData, string $include) : array
    {
        $isIncludeAllowed = $this->policyAllowsInclusion($data, $include);

        if ($isIncludeAllowed && $resource = $this->callIncludeMethod($scope, $include, $data)) {
            $childScope = $scope->embedChildScope($include, $resource);

            if ($childScope->getResource() instanceof Primitive) {
                $includedData[$include] = $childScope->transformPrimitiveResource();
            } else {
                $includedData[$include] = $childScope->toArray();
            }
        }

        return $includedData;
    }

    private function policyAllowsInclusion(Model $data, string $include) : bool
    {
        $included_attribute = studly_case($include);
        $included_data = $data->$included_attribute;
        $policy_function = function ($item) { return app(Gate::class)->forUser(currentUser())->check('view', $item); };

        if ($included_data instanceof Model) {
            return $policy_function($included_data);
        }

        if ($included_data instanceof Collection) {
            return $included_data->every($policy_function);
        }

        // Edge case for Policies without a model, we use the 'include' string and the parent model.
        if (is_array($included_data)) {
            return app(Gate::class)->forUser(currentUser())->check('view', [$include, $data]);
        }

        return false;
    }
}
