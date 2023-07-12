# Wakajob Plugin #

Wakajob gère les jobs nécessite Utils

### Background Job Manager

Crée des JOB et les retrouver 

Regarder testJob pour un exemple

Voici un exemple

```php
<?php

class SomeBehavior {
    public function onCreateMails(){
    
        $job = new Waka\Mailer\Jobs\MyJobClass();
        $jobManager = \App::make('Waka\Wakajob\Classes\JobManager');
        $jobManager->dispatch($job, "Email en cours d'envoi");
        
    }
}
```

If you do not want to clutter your controller (eg if you have job that is run every few minutes), you can use 

```
$jobManager->isSimpleJob(true);
```  
before dispatching - this will remove **successful** job from DB at the end.
