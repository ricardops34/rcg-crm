export const Audited = () => {
  return (constructor: Function) => {
    constructor.prototype['isAudited'] = true;
  };
};
