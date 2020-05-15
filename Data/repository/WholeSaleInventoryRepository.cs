using PointOfSale.Data.interfaces;
using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace PointOfSale.Data.repository
{
	public class WholeSaleInventoryRepository : IInventory
	{
		public void AddInventory(Inventory inventory)
		{
			throw new NotImplementedException();
		}

		public void DeleteInventory(int id)
		{
			throw new NotImplementedException();
		}

		public IEnumerable<Inventory> GetInventories()
		{
			throw new NotImplementedException();
		}

		public Inventory GetInventory(int id)
		{
			throw new NotImplementedException();
		}

		public void UpdateInventory(Inventory inventory)
		{
			throw new NotImplementedException();
		}
	}
}