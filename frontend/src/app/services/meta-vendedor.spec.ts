import { TestBed } from '@angular/core/testing';

import { MetaVendedor } from './meta-vendedor';

describe('MetaVendedor', () => {
  let service: MetaVendedor;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(MetaVendedor);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
