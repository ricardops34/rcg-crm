import { SetMetadata } from '@nestjs/common';

export const PERMISSION_KEY = 'permission';
export const RequirePermission = (controllerName: string) => SetMetadata(PERMISSION_KEY, controllerName);
