import { Injectable, inject } from '@angular/core';
import { PoLookupFilter } from '@po-ui/ng-components';
import { Observable, of } from 'rxjs';
import { RoutesRegistryService, RouteRegistryItem } from './routes-registry.service';

@Injectable({
  providedIn: 'root'
})
export class RoutesLookupService implements PoLookupFilter {
  private routesRegistry = inject(RoutesRegistryService);

  getFilteredItems(params: { filter: string, page: number, pageSize: number }): Observable<any> {
    const allItems = this.routesRegistry.getRoutes();
    const filterValue = params.filter ? params.filter.toLowerCase().trim() : '';

    const filtered = allItems.filter(item => 
      item.value.toLowerCase().includes(filterValue) ||
      item.label.toLowerCase().includes(filterValue) ||
      item.path.toLowerCase().includes(filterValue) ||
      (item.module && item.module.toLowerCase().includes(filterValue))
    );

    // Paginação simples em memória
    const page = params.page || 1;
    const pageSize = params.pageSize || 10;
    const start = (page - 1) * pageSize;
    const end = start + pageSize;
    const items = filtered.slice(start, end);
    const hasNext = filtered.length > end;

    return of({
      items: items,
      hasNext: hasNext
    });
  }

  getObjectByValue(value: string): Observable<RouteRegistryItem | null> {
    if (!value) {
      return of(null);
    }
    const route = this.routesRegistry.getRouteByController(value);
    return of(route);
  }
}
