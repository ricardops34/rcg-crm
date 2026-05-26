import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';
import { provideRouter } from '@angular/router';

import { VendedorFormComponent } from './vendedor-form';

describe('VendedorFormComponent', () => {
  let component: VendedorFormComponent;
  let fixture: ComponentFixture<VendedorFormComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [VendedorFormComponent],
      providers: [provideHttpClient(), provideRouter([])],
    }).compileComponents();

    fixture = TestBed.createComponent(VendedorFormComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
