import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';
import { provideRouter } from '@angular/router';

import { MvcListComponent } from './mvc-list';

describe('MvcListComponent', () => {
  let component: MvcListComponent;
  let fixture: ComponentFixture<MvcListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MvcListComponent],
      providers: [provideHttpClient(), provideRouter([])],
    }).compileComponents();

    fixture = TestBed.createComponent(MvcListComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
