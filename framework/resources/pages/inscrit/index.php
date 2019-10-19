<?php


add_action('tr_table_search_model', function ($model){
    $model->whereMeta('year_participe', '=', tr_options_field('options.ins_year'));
});

$tables = tr_tables(25, new \App\Models\Inscrit);

$tables->setOrder('id', 'DESC');

$tables->addCheckboxes();

$tables->setColumns('post_title', [
    'post_title' => [
        'label' => 'Nom du candidat',
        'sort' => true
    ],

    'meta.codeins' => [
        'label' => 'Code Candidat',
        'sort' => false
    ],

    'meta.datenais' => [
        'label' => 'Age',
        'sort' => true,
        'callback' => function($value){
            list($jour, $mois, $annee) = preg_split('[/]', $value);
            $today['mois'] = date('n');
            $today['jour'] = date('j');
            $today['annee'] = date('Y');
            $annees = $today['annee'] - $annee;
            if ($today['mois'] <= $mois) {
                if ($mois == $today['mois']) {
                    if ($jour > $today['jour'])
                        $annees--;
                }
                else
                    $annees--;
            }

            return $annees . ' ans';
        }

    ],
    'meta.position' => [
        'label' => 'Ville',
        'sort' => true
    ],

    'meta.email' => [
        'label' => 'Email',
        'sort' => false
    ],

    'meta.phone' => [
        'label' => 'Telephone',
        'sort' => false
    ]
]);

$tables->appendSearchFormFilters(function(){
    // add custom HTML to search table form
    echo ' Mon contenu';
});


$tables->render();