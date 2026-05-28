import { TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';

import { AuthService } from './auth';
import { AuthResponse } from './models/auth.model';

describe('AuthService', () => {
  let service: AuthService;

  beforeEach(() => {
    localStorage.clear();
    TestBed.configureTestingModule({
      providers: [provideHttpClient()],
    });
    service = TestBed.inject(AuthService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should return the default login logo when nothing was persisted', () => {
    expect(service.getLoginLogo()).toBe('logo_bj.png');
  });

  it('should persist the active unit logo after a successful login', () => {
    const response: AuthResponse = {
      accessToken: 'token',
      user: {
        login: 'teste',
        unit: {
          id: 1,
          name: 'Matriz',
          logo: 'data:image/png;base64,abc123'
        }
      }
    };

    service.handleAuthResponse(response);

    expect(service.getLoginLogo()).toBe('data:image/png;base64,abc123');
  });
});
