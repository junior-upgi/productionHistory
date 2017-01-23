<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Service\TemplateService;

/**
 * Class TemplateController
 * @package App\Http\Controllers
 */
class TemplateController extends BaseController
{
    /**
     * @var TemplateService
     */
    public $service;

    /**
     * TemplateController constructor.
     *
     * @param TemplateService $service
     * @internal param TemplateRepository $template
     * @internal param ItemRepository $item
     */
    public function __construct(TemplateService $service)
    {
        $this->service = $service;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getTemplate()
    {
        $data = $this->service->template->getTemplate(request()->input('id'))->first()->toArray();
        return $data;
    }

    /**
     *
     *
     * @return mixed
     */
    public function searchTemplate()
    {
        $list = $this->service->template->getTemplateList()
            ->where('name', 'like', '%' . iconv('utf8', 'big5', request()->input('name')) . '%')
            ->orderBy('name')->get()->toArray();
        return $list;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getTemplateList()
    {
        $list = $this->service->template->getTemplateList()
            ->orderBy('name')->get()->toArray();
        return $list;
    }


    /**
     *
     *
     * @return array
     */
    public function getTemplateItem()
    {
        return [
            'template' => $this->service->template->getTemplate(request()->input('id'))->first(),
            'itemList' => $this->service->template->getNonSelectItem(request()->input('id'))->get(),
            'selectList' => $this->service->template->getSelectedItem(request()->input('id'))->get(),
        ];
    }

    /**
     *
     *
     * @return mixed
     */
    public function insertTemplate()
    {
        return $this->service->insertTemplate(request()->input());
    }

    /**
     *
     *
     * @return mixed
     */
    public function updateTemplate()
    {
        return $this->service->updateTemplate(request()->input());
    }

    /**
     *
     *
     * @return mixed
     */
    public function deleteTemplate()
    {
        return $this->service->template->deleteTemplate(request()->input('id'));
    }
}
