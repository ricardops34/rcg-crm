import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MvcList } from './mvc-list';

describe('MvcList', () => {
  let component: MvcList;
  let fixture: ComponentFixture<MvcList>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MvcList],
    }).compileComponents();

    fixture = TestBed.createComponent(MvcList);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
