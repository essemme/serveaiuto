<div class="search">			

<?php
echo $this->Form->create('SearchIndex', array(
  
    'url' => '/searchable/search_indexes/index',
//        array(
//            'plugin' => 'searchable',
//            'controller' => 'search_indexes',
//            'action' => 'index'
//        ),
    'id' => 'search_form',

));
echo $this->Form->input('term', array('label' => false, 'id' => 'SearchIndexTerm', 'div' => false));// 'id' => 'SearchSearch', 'id' => 'SearchIndexTerm', 'name' => 'term',
echo $this->Form->input('type', array('value' => 'All','type' => 'hidden'));
?>
<input id="search_submit" type="submit" value="" name="SearchSubmit">
<?php 
//echo $this->Form->submit(null, array('div' => false));
echo $this->Form->end();
?>
</div>