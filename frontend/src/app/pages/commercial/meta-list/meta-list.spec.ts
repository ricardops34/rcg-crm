import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';
import { provideRouter } from '@angular/router';

import { MetaListComponent } from './meta-list';

describe('MetaListComponent', () => {
  let component: MetaListComponent;
  let fixture: ComponentFixture<MetaListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MetaListComponent],
      providers: [provideHttpClient(), provideRouter([])],
    }).compileComponents();

    fixture = TestBed.createComponent(MetaListComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
