import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import autoAnimate from '@formkit/auto-animate';

Alpine.directive('animate', el => autoAnimate(el));

Livewire.start()
