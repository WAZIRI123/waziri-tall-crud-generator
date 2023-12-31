<?php

namespace WAZIRITALLCRUDGENERATOR\Http\GenerateCode;

use Illuminate\Support\Str;

class ComponentCode extends BaseCode
{
    public $tallProperties;

    public function __construct(TallProperties $tallProperties)
    {
        $this->tallProperties = $tallProperties;
    }

    public function getOtherModelsCode()
    {
        $models = $this->tallProperties->getOtherModels();

        return $models->map(function ($m) {
            return $this->getUseModelCode($m);
        })->implode('');
    }

    public function getSortCode()
    {
        $code = [
            'vars' => '',
            'query' => '',
            'method' => '',
        ];
        if ($this->tallProperties->isSortingEnabled()) {
            $code['vars'] = $this->getSortingVars();
            $code['query'] = $this->getSortingQuery();
            $code['method'] = $this->getSortingMethod();
        }

        return $code;
    }

    public function getSortingVars()
    {
        return str_replace(
            '##SORT_COLUMN##',
            $this->tallProperties->getDefaultSortableColumn(),
            Template::getSortingVariables()
        );
    }

    public function getSortingQuery()
    {
        return Template::getSortingQuery();
    }

    public function getSortingMethod()
    {
        return Template::getSortingMethod();
    }

    public function getSearchCode()
    {
        $code = [
            'vars' => '',
            'query' => '',
            'method' => '',
        ];
        if ($this->tallProperties->isSearchingEnabled()) {
            $code['vars'] = $this->getSearchVars();
            $code['query'] = $this->getSearchQuery();
            $code['method'] = $this->getSearchMethod();
        }

        return $code;
    }

    public function getSearchVars()
    {
        return Template::getSearchVariables();
    }

    public function getSearchQuery()
    {
        $whereClause = $this->getSearchWhereClause();

        return str_replace(
            '##WHERE_CLAUSE##',
            $whereClause->prependAndJoin($this->newLines(1, 6), $this->indent(5)),
            Template::getSearchQueryCode()
        );
    }

    public function getSearchWhereClause()
    {
        $whereClause = collect();
        $searchableColumns = $this->tallProperties->getSearchableColumns();
        $isFirst = true;
        foreach ($searchableColumns as $column) {
            $whereClause->push(
                str_replace(
                    [
                        '##QUERY##',
                        '##COLUMN##',
                    ],
                    [
                        $isFirst ? '$query->where' : '->orWhere',
                        $column,
                    ],
                    Template::getSearchQueryWhereClause(),
                )
            );
            $isFirst = false;
        }

        return $whereClause;
    }

    public function getSearchMethod()
    {
        return Template::getSearchMethod();
    }

    public function getPaginationDropdownCode()
    {
        $code = [
            'method' => '',
        ];
        if ($this->tallProperties->isPaginationDropdownEnabled()) {
            $code['method'] = $this->getPaginationDropdownMethod();
        }

        return $code;
    }

    public function getPaginationDropdownMethod()
    {
        return Template::getPaginationDropdownMethod();
    }

    public function getPaginationCode()
    {
        $code = [
            'vars' => '',
        ];

        $code['vars'] = $this->getPaginationVars();

        return $code;
    }

    public function getPaginationVars()
    {
        return str_replace(
            '##PER_PAGE##',
            $this->tallProperties->getRecordsPerPage(),
            Template::getPaginationVariables()
        );
    }

    public function getWithQueryCode()
    {
        $models = $this->tallProperties->getEagerLoadModels();
        if ($models->isEmpty()) {
            return '';
        }

        return str_replace(
            '##RELATIONS##',
            $this->wrapInQuotesAndJoin($models),
            Template::getWithQueryCode()
        );
    }

    public function getWithCountQueryCode()
    {
        $models = $this->tallProperties->getEagerLoadCountModels();
        if ($models->isEmpty()) {
            return '';
        }

        return str_replace(
            '##RELATIONS##',
            $this->wrapInQuotesAndJoin($models),
            Template::getWithCountQueryCode()
        );
    }

    public function getHideColumnsCode()
    {
        $code = [
            'vars' => '',
            'init' => '',
            'method' => '',
        ];
        if ($this->tallProperties->isHideColumnsEnabled()) {
            $code['vars'] = $this->getHideColumnVars();
            $code['init'] = $this->getHideColumnInitCode();
        }

        return $code;
    }

    public function getHideColumnVars()
    {
        return $this->getAllColumnsVars() .
            $this->newLines() .
            self::getEmtpyArray('selectedColumns');
    }

    public function getAllColumnsVars()
    {
        $columns = $this->tallProperties->getListingColumns();
        $labels = $columns->map(function ($c) {
            return $c['label'];
        });

        return str_replace(
            '##COLUMNS##',
            $this->wrapInQuotesAndJoin($labels),
            Template::getAllColumns()
        );
    }

    public function getHideColumnInitCode()
    {
        return Template::getHideColumnInitCode();
    }

    public function getBulkActionsCode()
    {
        $code = [
            'vars' => '',
            'method' => '',
        ];
        if ($this->tallProperties->isBulkActionsEnabled()) {
            $code['vars'] = $this->getBulkActionsVars();
            $code['method'] = $this->getBulkActionMethod();
        }

        return $code;
    }

    public function getBulkActionMethod()
    {
        return str_replace(
            [
                '##MODEL##',
                '##PRIMARY_KEY##',
                '##COLUMN##',
            ],
            [
                $this->tallProperties->getModelName(),
                $this->tallProperties->getPrimaryKey(),
                $this->tallProperties->getBulkActionColumn(),
            ],
            Template::getBulkActionMethod()
        );
    }

    public function getBulkActionsVars()
    {
        return $this->newLines() .
            self::getEmtpyArray('selectedItems');
    }

    public function getFilterCode()
    {
        $code = [
            'vars' => '',
            'init' => '',
            'query' => '',
            'method' => '',
            'mount' => '',
        ];
        if ($this->tallProperties->isFilterEnabled()) {
            $code['vars'] = $this->getFilterVars();
            $code['init'] = $this->getFilterInitCode();
            $code['query'] = $this->getFilterQuery();
            $code['method'] = $this->getFilterMethod();
            $code['mount'] = $this->getFilterMount();
        }

        return $code;
    }

    public function getFilterVars()
    {
        $vars = collect();
        $vars->push(self::getEmtpyArray('filters'));
        $vars->push(self::getEmtpyArray('selectedFilters'));

        return $vars->prependAndJoin($this->newLines());
    }

    public function getFilterInitCode()
    {
        $code = $this->getSelfFilterInitCode() .
            $this->getDateFilterInitCode() .
            $this->getRelationFilterInitCode();

        return str_replace(
            '##CODE##',
            $code,
            Template::getInitFilterCode()
        );
    }

    public function getSelfFilterInitCode()
    {
        $filters = collect();
        foreach ($this->tallProperties->selfFilters as $f) {
            $filterOptions = $this->generateFilterOptionsFromJson($f);
            if ($filterOptions->isEmpty()) {
                continue;
            }
            $filters->push(
                str_replace(
                    [
                        '##KEY##',
                        '##LABEL##',
                        '##OPTIONS##',
                    ],
                    [
                        $this->getFilterColumnName($f),
                        $this->getFilterLabelName($f),
                        $filterOptions->prependAndJoin($this->newLines(1, 5)),
                    ],
                    Template::getSelfFilterInitCode()
                )
            );
        }

        if ($filters->isEmpty()) {
            return '';
        }

        return str_replace(
            '##FILTERS##',
            $filters->prependAndJoin($this->newLines(1, 1)) . $this->newLines(1, 2),
            Template::getFilterInitTemplate()
        );
    }

    public function getDateFilterInitCode()
    {
        $filter = $this->tallProperties->dateFilters->map(function ($f, $i) {
            return str_replace(
                [
                    '##COLUMN##',
                    '##LABEL##',
                ],
                [
                    $f['column'] . '_' . $i,
                    $f['label'],
                ],
                Template::getDateFilterInitCode()
            );
        })->filter();

        if ($filter->isEmpty()) {
            return '';
        }

        return $filter->prependAndJoin($this->newLines());
    }

    public function generateFilterOptionsFromJson($f)
    {
        $filterOptions = collect();
        $options = json_decode($f['options']);
        if (is_null($options)) {
            return $filterOptions;
        }

        foreach ($options as $k => $v) {
            $filterOptions->push(
                str_replace(
                    [
                        '##KEY##',
                        '##LABEL##',
                    ],
                    [
                        $k,
                        $v,
                    ],
                    Template::getKeyLabelTemplate()
                )
            );
        }

        return $filterOptions;
    }

    public function getRelationFilterInitCode()
    {
        $filters = $this->tallProperties->btmFilters->merge($this->tallProperties->belongsToFilters);

        return $filters->map(function ($f) {
            return str_replace(
                [
                    '##VAR##',
                    '##MODEL##',
                    '##COLUMN##',
                    '##OWNER_KEY##',
                    '##FOREIGN_KEY##',
                    '##LABEL##',
                    '##EMPTY_FILTER_KEY##',
                    '##IS_MULTIPLE##',
                ],
                [
                    Str::plural($f['relation']),
                    $this->tallProperties->getModelName($f['modelPath']),
                    $f['column'],
                    $this->getFilterOwnerKey($f),
                    $this->getFilterForeignKey($f),
                    $this->getFilterLabelName($f),
                    $f['isMultiple'] ? '' : Template::getEmptyFilterKey(),
                    $f['isMultiple'] ? '' : '//',
                ],
                Template::getRelationFilterInitTemplate()
            );
        })->implode('');
    }

    public function getFilterQuery()
    {
        return $this->getSelfFilterQuery() . $this->getBtmFilterQuery() . $this->getDateFilterQuery();
    }

    public function getSelfFilterQuery()
    {
        $filters = $this->tallProperties->selfFilters
            ->merge($this->tallProperties->belongsToFilters);

        return $filters->map(function ($f) {
            return str_replace(
                [
                    '##COLUMN##',
                    '##CLAUSE##',
                ],
                [
                    $this->getFilterColumnName($f),
                    $f['isMultiple'] ? 'whereIn' : 'where',
                ],
                Template::getFilterQueryTemplate()
            );
        })->prependAndJoin($this->newLines());
    }

    public function getBtmFilterQuery()
    {
        $filters = $this->tallProperties->btmFilters;

        return $filters->map(function ($f) {
            return str_replace(
                [
                    '##COLUMN##',
                    '##RELATION##',
                    '##RELATED_KEY##',
                    '##TABLE##',
                    '##CLAUSE##',
                ],
                [
                    $f['relation'] . '_' . $f['relatedKey'],
                    $f['relation'],
                    $f['relatedKey'],
                    $f['relatedTableName'],
                    $f['isMultiple'] ? 'whereIn' : 'where',
                ],
                Template::getFilterQueryBtmTemplate()
            );
        })->prependAndJoin($this->newLines());
    }


    public function getDateFilterQuery()
    {
        $filters = $this->tallProperties->dateFilters;

        return $filters->map(function ($f, $i) {
            return str_replace(
                [
                    '##LABEL##',
                    '##COLUMN##',
                    '##CLAUSE##',
                    '##OPERATOR##',
                ],
                [
                    $f['column'] . '_' . $i,
                    $f['column'],
                    $f['isMultiple'] ? 'whereIn' : 'where',
                    $f['operator'],
                ],
                Template::getDateFilterQueryTemplate()
            );
        })->prependAndJoin($this->newLines());
    }

    public function getFilterMethod()
    {
        $filters = $this->tallProperties->btmFilters->merge($this->tallProperties->belongsToFilters);
        $resetMultiFilters = $filters->map(function ($f) {
            return $this->getResetMultipleFilter($f);
        })->implode('');

        return str_replace(
            '##RESET_MULTI_FILTER##',
            $resetMultiFilters,
            Template::getFilterMethodTemplate()
        );
    }

    public function getFilterMount()
    {
        return Template::getFilterMountCode();
    }

    public function getFilterColumnName($filter)
    {
        return (in_array($filter['type'], ['None', 'Date'])) ? $filter['column'] : $filter['foreignKey'];
    }

    public function getFilterLabelName($filter)
    {
        if (in_array($filter['type'], ['None', 'Date'])) {
            return Str::ucfirst($filter['column']);
        }

        return Str::ucfirst($filter['relation']);
    }

    public function getFilterOwnerKey($filter)
    {
        if ($filter['type'] == 'BelongsTo') {
            return $filter['ownerKey'];
        }

        return $filter['relatedKey'];
    }

    public function getFilterForeignKey($filter)
    {
        if ($filter['type'] == 'BelongsTo') {
            return $filter['foreignKey'];
        }

        return $filter['relation'] . '_' . $filter['relatedKey'];
    }

    public function getResetMultipleFilter($filter)
    {
        if (! $filter['isMultiple']) {
            return '';
        }

        return str_replace(
            '##FOREIGN_KEY##',
            $this->getFilterForeignKey($filter),
            Template::getResetMultipleFilter()
        );
    }
}
