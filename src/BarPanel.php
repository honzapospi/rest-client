<?php

/**
 * Copyright (c) Jan Pospisil (http://www.jan-pospisil.cz)
 */

namespace JP\RestClient;
use Nette\Utils\Html;
use Tracy\Debugger;
use Tracy\IBarPanel;

/**
 * BarPanel
 * @author Jan Pospisil
 */

class BarPanel extends \Nette\Object implements IBarPanel {

	private $queries = array();
	private $url;

	public function __construct($url){
		Debugger::getBar()->addPanel($this);
		$this->url = $url;
	}

	public function add(RestRequest $restRequest, Response $response){
		$queryEntity = new Query();
		$queryEntity->query = $restRequest->endpoint;
		$queryEntity->method = $restRequest->method;
		$queryEntity->data = $restRequest->params;
		$queryEntity->time = $response->execTime;
		$queryEntity->code = $response->code;
		$this->queries[] = $queryEntity;
		return $queryEntity;
	}

	public function getTab(){
		return Html::el("span")
			->addHtml(
				Html::el("img")->style("margin-top: 3; padding: 0 10px;")
					->src("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAARCAIAAABfOGuuAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjk5MzJGODA0MjI0MjExRTM4NkQ4Qzk5NDJEQTRFREExIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjk5MzJGODA1MjI0MjExRTM4NkQ4Qzk5NDJEQTRFREExIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTkzMkY4MDIyMjQyMTFFMzg2RDhDOTk0MkRBNEVEQTEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OTkzMkY4MDMyMjQyMTFFMzg2RDhDOTk0MkRBNEVEQTEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz76Kql/AAACYElEQVR42oRSy2sTQRifmZ0ku3mVpE0TmrZCIynVgORgQFFE0EIRFLxVLx60iAcFL4IXxX+h9iIUVPBcLehFpOBbxFr0ZFpaTVtJc8hjk2z2NTN+u2kMCDYzs7sz8/2+x+/7LWbM/lFUZz9srbcwIxj9f1AuMiF0/di+ZMSPV4vVqQdfSnJUDsh7OSEkEGqqzQnSeHH1MH3yqVCrsmSwztVaT7d+Qtbr+sLXbbpV0RVJQpwT1GM4QTlXiFQotyhG3RxcQEREsHMDW2e6+DZlsLbBYO/m4Fx4JCIRzF2wRJBXIj5KwKdlMbjxOtZOe9oBDZuPxwP3L2ZqLevKo+87qnH7TGryYAwCtUz28N3m63zl8eVDz7+V7jzLOxW1k4Lb2Wx8LObPjvYdT0c0k41ElFQscG8xD9ZbU/sHQh44JsK+XSLwAMhH8flsYv5N4f1a+UIuCVDTdoo9l00MR5S1UlMzOCDtNgEoknFe1azJAwODYV86EewPetPxwEhU1iwO9C3G55Z+LiwXZSp1+ylAemCP8cyJUTiWm6Zhs/FEcDo35JEcwN3F1e2K7vdKmWQIjrKH6BZnQlDodEihnzeqQHdu6VefQm9OjpVUM7+jlVTDsjk0k3Hxu6rPvtpY2VQdYQTGl+Y/Pl2puVsc8ElwWzdsD2iHMdALyRR3RGvozEuJabNrJ4eo2xgUlOlu6RiFO3so6a+qECesUFcqqBFR0FfAF+AC9R7YSQvdIUdS0YZWR8wS3N57IVi2aRra0VSUTueG3+aLL5cLQY/7kyMh2u9/08DEdYZvnE6fmhj8I8AAjY8zur1OuHMAAAAASUVORK5CYII=")
			);
	}

	public function getPanel(){
		$s = '';
		foreach ($this->queries as $i => $query) {
			$endpoint = $query->query;
			$sep = preg_match('#\?#', $this->url) ? '&' : '?';
			if($query->method == Rest::GET){
				$endpoint = Html::el('a')
					->setAttribute('href', $this->url.$query->query.$sep.http_build_query($query->data))
					->setHtml($query->query)
					->setAttribute('target', '_blank');
			}
			$s .= '<tr><td>' . number_format($query->time, 3).'</td>';
			$s .= '<td>' . $endpoint.'</td>';
			$s .= '<td>' .$query->method.'</td>';
			$s .= '<td>' .$query->code.'</td>';
			$params = '';
			foreach($query->data as $i => $row){
				$row = is_array($row) ? print_r($row, true) : $row;
				$params .= $i.' = '.$row.'<br />';
			}
			$s .= '<td>'.$params.'</td>';
			$s .= '</tr>';
		}

		$url = Html::el('a')
			->setHtml($this->url)
			->setAttribute('href', $this->url)
			->setAttribute('target', '_blank');

		return '
			<h1>REST API</h1>
			<h3>URL: '.$url.'</h3>
			<div class="nette-inner">
			<table>
				<tr>
					<th>Time [ms]</th>
					<th>Query</th>
					<th>Method</th>
					<th>Code</th>
					<th>Params</th>
				</tr>' . $s . '
			</table>
			</div>';
	}


}
