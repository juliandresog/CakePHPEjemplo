<h1>Blog articles</h1>
<p><?= $this->Html->link("Add Article", ['action' => 'add']) ?></p>
<table>
<tr>
<th>Id</th>
<th>Title</th>
<th>Created UTC</th>
<th>Created COT</th>
<th>Action</th>
</tr>
<?php foreach ($articles as $article): ?>
<tr>
<td><?= $article->id ?></td>
<td>
<?= $this->Html->link($article->title, ['action' => 'view', $article->id]) ?>
</td>
<td>
<?= $article->created->format(DATE_RFC850) ?>
</td>
<td>
<?php 
    echo $this->Time->format(
        $article->created,
        'yyyy-MM-dd HH:mm:ss',//\IntlDateFormatter::SHORT,
        null,
        'America/Bogota'
      ); 
?>
</td>
<td>
<?= $this->Form->postLink(
'Delete',
['action' => 'delete', $article->id],
['confirm' => 'Are you sure?'])
?> 
<?= $this->Html->link('Edit', ['action' => 'edit', $article->id]) ?>
</td>
</tr>
<?php endforeach; ?>
</table>