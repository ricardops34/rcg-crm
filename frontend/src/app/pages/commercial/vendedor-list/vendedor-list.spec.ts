import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';
import { provideRouter } from '@angular/router';

import { VendedorListComponent } from './vendedor-list';

describe('VendedorListComponent', () => {
  let component: VendedorListComponent;
  let fixture: ComponentFixture<VendedorListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [VendedorListComponent],
      providers: [provideHttpClient(), provideRouter([])],
    }).compileComponents();

    fixture = TestBed.createComponent(VendedorListComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
