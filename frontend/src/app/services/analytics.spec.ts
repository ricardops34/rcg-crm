import { TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';

import { AnalyticsService } from './analytics';

describe('AnalyticsService', () => {
  let service: AnalyticsService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient()],
    });
    service = TestBed.inject(AnalyticsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
