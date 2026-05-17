import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MetaList } from './meta-list';

describe('MetaList', () => {
  let component: MetaList;
  let fixture: ComponentFixture<MetaList>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MetaList],
    }).compileComponents();

    fixture = TestBed.createComponent(MetaList);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
