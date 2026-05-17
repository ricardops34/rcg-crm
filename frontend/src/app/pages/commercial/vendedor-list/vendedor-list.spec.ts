import { ComponentFixture, TestBed } from '@angular/core/testing';

import { VendedorList } from './vendedor-list';

describe('VendedorList', () => {
  let component: VendedorList;
  let fixture: ComponentFixture<VendedorList>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [VendedorList],
    }).compileComponents();

    fixture = TestBed.createComponent(VendedorList);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
