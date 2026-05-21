export function Audited() {
  return function (constructor: Function) {
    constructor.prototype.isAudited = true;
  };
}
