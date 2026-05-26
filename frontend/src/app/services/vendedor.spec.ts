import { TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';

import { VendedorService } from './vendedor';

describe('VendedorService', () => {
  let service: VendedorService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient()],
    });
    service = TestBed.inject(VendedorService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
