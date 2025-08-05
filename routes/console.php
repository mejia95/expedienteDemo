<?php

use App\Console\Commands\NotificacionAccesoMedicoPasante;
use App\Console\Commands\SuspencionAccesoAutomatico;
use Illuminate\Support\Facades\Schedule;

Schedule::command(NotificacionAccesoMedicoPasante::class)->everyFiveMinutes();
Schedule::command(SuspencionAccesoAutomatico::class)->everyFiveMinutes();