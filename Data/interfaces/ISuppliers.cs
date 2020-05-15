using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Models
{
	interface ISuppliers
	{
		IEnumerable<Suppliers> GetSuppliers();
		void AddSuppliers(Suppliers suppliers);
		Suppliers GetSupplier(int id);
		void UpdateSupplier(Suppliers suppliers);
		void DeleteSupplier(int id);
	}
}
