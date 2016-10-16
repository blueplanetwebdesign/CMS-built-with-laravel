<?php
namespace Bpwd\Admin\Helpers;
use Illuminate\Pagination\BootstrapThreePresenter;

class Pagination extends BootstrapThreePresenter {

    public function render()
    {

        if ($this->hasPages()) {
            return sprintf(
                '<div class="pagination">%s %s %s %s %s</div>',
                $this->getFirstButton(),
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton(),
                $this->getLastButton()
            );
        }
        return "";
    }

    public function getLastButton()
    {
        $text = 'Last';

        if($this->paginator->lastPage() == $this->paginator->currentPage()){
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url($this->paginator->lastPage());

        return $this->getPageLinkWrapper($url, $text, 'last');
    }

    public function getFirstButton()
    {
        $text = 'First';

        if(1 == $this->paginator->currentPage()){
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url(1);

        return $this->getPageLinkWrapper($url, $text, 'first');
    }

    public function getButtonPre()
    {
        $url = $this->paginator->previousPageUrl();
        $btnStatus = '';

        if(empty($url)){
            $btnStatus = 'disabled';
        }

        return $btn = "<a href='".$url."'><button class='btn btn-success ".$btnStatus."'><i class='glyphicon glyphicon-chevron-left pagi-margin'></i><i class='glyphicon glyphicon-chevron-left'></i> Previous </button></a>";
    }

    public function getButtonNext()
    {
        $url = $this->paginator->nextPageUrl();
        $btnStatus = '';

        if(empty($url)){
            $btnStatus = 'disabled';
        }

        return $btn = "<a href='".$url."'><button class='btn btn-success ".$btnStatus."'>Next <i class='glyphicon glyphicon-chevron-right pagi-margin'></i><i class='glyphicon glyphicon-chevron-right'></i></button></a>";
    }
}