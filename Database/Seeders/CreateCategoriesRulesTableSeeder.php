<?php

namespace Modules\Requestable\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Modules\Requestable\Repositories\CategoryRuleRepository;

class CreateCategoriesRulesTableSeeder extends Seeder
{

  private $categoryRuleRepository;


  public function __construct(
    CategoryRuleRepository $categoryRuleRepository
  ){
      $this->categoryRuleRepository = $categoryRuleRepository;
  }

  /**
   * Run the database seeds.
   *
   * @return void
  */
  public function run(){
    
    Model::unguard();

    $categories = config('asgard.requestable.config.categories-rules');

    try{

      if(!is_null($categories)){
        foreach ($categories as $category) {

          $existCategory = $this->findCategory($category['systemName']);

          if(!isset($existCategory->id)) {
            $this->createCategory($category); 
          }
          
        }
      }

    }catch(\Exception $e){
      \Log::error('Requestable: Seeder|CreateCategoriesRules|Message: '.$e->getMessage());
      dd($e);
    }

   
  }

  /*
  * Find category
  */
  public function findCategory($systemName){

    $params = [
      "filter" => ["field" => "system_name"],
      "include" => [],
      "fields" => [],
    ];

    $category = $this->categoryRuleRepository->getItem($systemName, json_decode(json_encode($params)));

    return $category;
  }

  /*
  * Create 
  */
  public function createCategory($data){

    $parentId = null;
    if(isset($data['parentSystemName'])){
      $categoryParent = $this->findCategory($data['parentSystemName']);
      $parentId = $categoryParent->id;
    }

    $dataToCreate = [
      'system_name' => $data['systemName'],
      'parent_id' => $parentId,
      'status' => $data['status'] ?? 1,    
      'es' => [
        'title' => $data['es']['title'] ?? null,   
      ],
      'en' => [
        'title' => $data['en']['title'] ?? null      
      ],
      'options' => isset($data['options']) ? $data['options'] : null,
      'formFields' => isset($data['formFields']) ? $data['formFields'] : null
    ];

    $categoryCreated = $this->categoryRuleRepository->create($dataToCreate);

  }

  

}
