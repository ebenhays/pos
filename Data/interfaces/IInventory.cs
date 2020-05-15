using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Data.interfaces
{
	interface IInventory
	{
		IEnumerable<Inventory> GetInventories();
		void AddInventory(Inventory inventory);
		Inventory GetInventory(int id);
		void UpdateInventory(Inventory inventory);
		void DeleteInventory(int id);
	}
}
