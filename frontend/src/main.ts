import { bootstrapApplication } from '@angular/platform-browser';
import 'temporal-polyfill/global';
import { appConfig } from './app/app.config';
import { AppComponent } from './app/app.component';

bootstrapApplication(AppComponent, appConfig)
  .catch((err) => console.error(err));
