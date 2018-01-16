<?php

namespace App\Lib\Fractal;

use Illuminate\Contracts\Auth\Access\Gate;;
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

    private function includeResourceIfAvailableAndPolicyAllowsInclusion(Scope $scope, object $data, array $includedData, string $include) : array
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

    private function policyAllowsInclusion(object $data, string $include) : bool
    {
        $model_name = (new ReflectionClass($data))->getShortName();
        $policy_class_name = 'App\\Policies\\' . studly_case($model_name) . 'Policy';
        $policy_method_name = 'include'.ucfirst($include);

        if (!currentUser() || !class_exists($policy_class_name) || !method_exists(new $policy_class_name, $policy_method_name)) {
            return true;
        };

        if (method_exists(new $policy_class_name, 'before')) {
            return app(Gate::class)->forUser(currentUser())->check('before', $data);
        };

        return app(Gate::class)->forUser(currentUser())->check($policy_method_name, $data);
    }
}
