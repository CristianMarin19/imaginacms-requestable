<?php

namespace Modules\Requestable\Exports\Reports;

class detailedReport
{
 
  private $requestableExport;
  private $log = "Requestable:: Exports|detailedReport|";

  public function __construct($requestableExport)
  {
    $this->requestableExport = $requestableExport;
    \Log::info($this->log);
  }

   /*
  * Get Last comment and add to item
  */
  public function addLastCommentToItem($item,$baseItem)
  {

    //\Log::info($this->log."addLastCommentToItem");

    $formatComment = "--";

    if(count($item->comments)>0){

      $lastComment = $item->comments->last();

      $formatLastComment = strip_tags($lastComment->comment);
      //\Log::info($formatLastComment);

      $formatComment = $formatLastComment." | Fecha: ".$lastComment->created_at->format('d-m-Y');
    }

    array_push($baseItem, $formatComment);

    return $baseItem;

  }

  /**
   * Heading to Report
   */
  public function getHeading()
  {

    $headingFields = [
        'ID',
        trans('requestable::requestables.table.category'),
        trans('requestable::requestables.table.status'),
        trans('requestable::requestables.table.type'),
        trans('requestable::requestables.table.requested by'),
        trans('requestable::requestables.table.created by')
    ];

    //Add Extra Fields
    if($this->requestableExport->showExtraFields)
      $headingFields = $this->requestableExport->addFieldsToHeading($headingFields);
    
    //Add only last comment
    array_push($headingFields, trans('requestable::requestables.table.last comment'));
     
    
    return $headingFields;

  }

  /**
   * Map (Row) to Report
   */ 
  public function getMap($item)
  {

      //\Log::info($this->log."getMap|id: ".$item->id);

     // Base Item Fields
     $baseItem = [
      $item->id ?? null,
      $item->category->title ?? null,
      $item->status->title ?? null,
      $item->type ?? null,
      $item->requestedBy ? $item->requestedBy->present()->fullname: null,
      $item->createdByUser->present()->fullname ?? null
    ];

    //Add Extra Fields
    if($this->requestableExport->showExtraFields)
      $baseItem = $this->requestableExport->addFieldsToItem($item,$baseItem);

    //Last Comment
    $baseItem = $this->addLastCommentToItem($item,$baseItem);

    return $baseItem;
  }

}
