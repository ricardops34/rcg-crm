import { TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';

import { MetaVendedorService } from './meta-vendedor';

describe('MetaVendedorService', () => {
  let service: MetaVendedorService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient()],
    });
    service = TestBed.inject(MetaVendedorService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
