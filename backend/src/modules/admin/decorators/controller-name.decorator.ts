import { SetMetadata } from '@nestjs/common';

export const ControllerName = (name: string) => SetMetadata('controller', name);
